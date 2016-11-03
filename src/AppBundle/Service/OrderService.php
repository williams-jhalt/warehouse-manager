<?php

namespace AppBundle\Service;

use AppBundle\Entity\Carton;
use AppBundle\Entity\CartonItem;
use AppBundle\Entity\SalesOrder;
use DateTime;

class OrderService {
    
    private $redis;
    
    /**
     * @var ErpOneConnectorService
     */
    private $erp;
    
    /**
     * @var ProductService
     */
    private $productService;
    
    /**
     * @var ConnectShipService
     */
    private $connectShipService;
    
    public function __construct(ErpOneConnectorService $erp, $redis, ProductService $productService, ConnectShipService $connectShipService) {        
        $this->redis = $redis;
        $this->erp = $erp;
        $this->productService = $productService;
        $this->connectShipService = $connectShipService;
    }    
    
    public function getOrder($manifestId) {

        $query = "FOR EACH oe_head NO-LOCK "
                . "WHERE oe_head.company_oe = '" . $this->erp->getCompany() . "' "
                . "AND oe_head.Manifest_id = '" . $manifestId . "'";

        $response = $this->erp->read($query, "oe_head.order,oe_head.rec_seq,oe_head.ord_date,oe_head.customer,oe_head.Manifest_id");
        
        $result = array();

        foreach ($response as $item) {
            
            $salesOrder = new SalesOrder();
            $salesOrder->setCustomerNumber($item->oe_head_customer);
            $salesOrder->setManifestId($item->oe_head_Manifest_id);
            $salesOrder->setOrderDate(new DateTime($item->oe_head_ord_date));
            $salesOrder->setOrderNumber($item->oe_head_order);
            $salesOrder->setRecordSequence($item->oe_head_rec_seq);
            
            $result[] = $salesOrder;
            
        }
        
        return $result[0];

    }
    
    public function getCartons($order, $seq = '0001') {

        $query = "FOR EACH ed_ucc128ln NO-LOCK "
                . "WHERE ed_ucc128ln.company_oe = '" . $this->erp->getCompany() . "' "
                . "AND ed_ucc128ln.order = '" . $order . "' "
                . "AND ed_ucc128ln.rec_seq = '" . $seq . "'";

        $response = $this->erp->read($query, "ed_ucc128ln.order,ed_ucc128ln.rec_seq,ed_ucc128ln.ucc,ed_ucc128ln.carton");
        
        $result = array();
        $uccs = array();
        
        foreach ($response as $erpItem) {
            if (array_search($erpItem->ed_ucc128ln_ucc, $uccs) !== false) {
                continue;
            }
            $carton = new Carton();
            $carton->setCartonNumber($erpItem->ed_ucc128ln_carton);
            $carton->setOrderNumber($erpItem->ed_ucc128ln_order);
            $carton->setRecordSequence($erpItem->ed_ucc128ln_rec_seq);
            $carton->setUcc($erpItem->ed_ucc128ln_ucc);
            $carton->setTrackingNumber($this->connectShipService->getTrackingNumber($erpItem->ed_ucc128ln_ucc));
            $result[] = $carton;
            $uccs[] = $erpItem->ed_ucc128ln_ucc;
        }
        
        return $result;
        
    }
    
    public function getItems($ucc) {

        $query = "FOR EACH ed_ucc128pk NO-LOCK "
                . "WHERE ed_ucc128pk.company_oe = '" . $this->erp->getCompany() . "' "
                . "AND ed_ucc128pk.ucc = '" . $ucc . "'";

        $response = $this->erp->read($query, "ed_ucc128pk.item,ed_ucc128pk.qty_shp");
        
        $result = array();
        
        foreach ($response as $erpItem) {
            $product = $this->productService->getByItemNumber($erpItem->ed_ucc128pk_item);
            
            $cartonItem = new CartonItem();
            $cartonItem->setBinLocation($product->getBinLocation());
            $cartonItem->setCommittedQuantity($product->getCommittedQuantity());
            $cartonItem->setDetail($product->getDetail());
            $cartonItem->setItemNumber($product->getItemNumber());
            $cartonItem->setName($product->getName());
            $cartonItem->setPrice($product->getPrice());
            $cartonItem->setQuantityShipped($erpItem->ed_ucc128pk_qty_shp);
            $cartonItem->setStockQuantity($product->getStockQuantity());
            
            $result[] = $cartonItem;
        }
        
        return $result;
        
    }
    
}

