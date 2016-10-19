<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductDetail;

class ProductService {

    /**
     *
     * @var ErpOneConnectorService
     */
    private $erp;

    /**
     * @var WholesaleService
     */
    private $wholesale;

    public function __construct(ErpOneConnectorService $erp, WholesaleService $wholesale) {
        $this->erp = $erp;
        $this->wholesale = $wholesale;
    }

    public function findBySearchTerms($searchTerms) {

        $query = "FOR EACH item NO-LOCK "
                . "WHERE item.company_it = '" . $this->erp->getCompany() . "' "
                . "AND item.sy_lookup MATCHES '*" . $searchTerms . "*', "
                . "EACH wa_item NO-LOCK WHERE "
                . "wa_item.company_it = item.company_it AND "
                . "wa_item.item = item.item";

        $response = $this->erp->read($query, "item.item,item.descr,wa_item.ship_location,wa_item.list_price,wa_item.qty_cmtd,wa_item.qty_oh");

        $result = array();

        foreach ($response as $item) {

            $wholesaleData = $this->wholesale->getProductData($item->item_item);
            $wholesaleImageData = $this->wholesale->getProductImageData($item->item_item);

            $images = array();

            foreach ($wholesaleImageData as $wholesaleImage) {
                $images[] = $wholesaleImage->image_thumb_url;
            }

            $product = new Product();
            $product->setItemNumber($item->item_item);
            $product->setName(join(" ", $item->item_descr));
            $product->setBinLocation($item->wa_item_ship_location);
            $product->setPrice($item->wa_item_list_price);
            $product->setStockQuantity($item->wa_item_qty_oh);
            $product->setCommittedQuantity($item->wa_item_qty_cmtd);

            $productDetail = new ProductDetail();
            $productDetail->setDescription($wholesaleData->description);
            $productDetail->setImages($images);

            $product->setDetail($productDetail);

            $result[] = $product;
        }

        return $result;
    }

    public function committedProducts($searchTerms = null, $offset = 0, $limit = 100) {

        $query = "FOR EACH item NO-LOCK "
                . "WHERE item.company_it = '" . $this->erp->getCompany() . "' ";

        if ($searchTerms !== null) {
            $query .= "AND item.sy_lookup MATCHES '*" . $searchTerms . "*'";
        }

        $query .= ", EACH wa_item NO-LOCK WHERE "
                . "wa_item.company_it = item.company_it AND "
                . "wa_item.item = item.item AND "
                . "wa_item.qty_cmtd > 0 BY wa_item.qty_cmtd DESCENDING";

        $response = $this->erp->read($query, "item.item,item.descr,wa_item.ship_location,wa_item.list_price,wa_item.qty_cmtd,wa_item.qty_oh", $offset, $limit);

        $result = array();

        foreach ($response as $item) {

            $product = new Product();
            $product->setItemNumber($item->item_item);
            $product->setName(join(" ", $item->item_descr));
            $product->setBinLocation($item->wa_item_ship_location);
            $product->setPrice($item->wa_item_list_price);
            $product->setStockQuantity($item->wa_item_qty_oh);
            $product->setCommittedQuantity($item->wa_item_qty_cmtd);

            $productDetail = new ProductDetail();

            $wholesaleData = $this->wholesale->getProductData($item->item_item);
            $wholesaleImageData = $this->wholesale->getProductImageData($item->item_item);

            if (!empty($wholesaleData)) {
                $productDetail->setDescription($wholesaleData->description);
            }

            if (!empty($wholesaleImageData)) {

                $images = array();

                foreach ($wholesaleImageData as $wholesaleImage) {
                    $images[] = $wholesaleImage->image_thumb_url;
                }

                $productDetail->setImages($images);
            }

            $product->setDetail($productDetail);

            $result[] = $product;
        }

        return $result;
    }

}
