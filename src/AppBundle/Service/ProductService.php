<?php

namespace AppBundle\Service;

use AppBundle\Entity\Product;

class ProductService {

    /**
     *
     * @var ErpOneConnectorService
     */
    private $erp;

    public function __construct(ErpOneConnectorService $erp) {
        $this->erp = $erp;
    }

    public function findBySearchTerms($searchTerms) {

        $query = "FOR EACH item NO-LOCK "
                . "WHERE item.company_it = '" . $this->erp->getCompany() . "' "
                . "AND item.sy_lookup MATCHES '*" . $searchTerms . "*', "
                . "EACH wa_item NO-LOCK WHERE "
                . "wa_item.company_it = item.company_it AND "
                . "wa_item.item = item.item";

        $response = $this->erp->read($query, "item.item,item.descr,wa_item.ship_location,wa_item.list_price");

        $result = array();

        foreach ($response as $item) {
            $product = new Product();
            $product->setItemNumber($item->item_item);
            $product->setName(join(" ", $item->item_descr));
            $product->setBinLocation($item->wa_item_ship_location);
            $product->setPrice($item->wa_item_list_price);
            $result[] = $product;
        }

        return $result;
    }

}
