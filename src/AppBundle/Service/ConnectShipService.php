<?php

namespace AppBundle\Service;

use Williams\ConnectShip\AMPServices;
use Williams\ConnectShip\ListCarriersRequest;
use Williams\ConnectShip\SearchRequest;

class ConnectShipService {

    /**
     * @var AMPServices
     */
    private $client;

    public function __construct($wsdl_url) {
        $this->client = new AMPServices(array('soap_version' => SOAP_1_2), $wsdl_url);
    }

    public function getTrackingNumber($ucc) {
        
        $service = $this->client;
        
        $carriersResponse = $service->ListCarriers(new ListCarriersRequest(null, null, null, null));

        foreach ($carriersResponse->getResult()->getResultData()->getItem() as $carrier) {
            $searchRequest = new SearchRequest($carrier->getSymbol(), null, null, null, null, null, null);
            $searchRequest->setFilters(array('consigneeReference' => $ucc));
            $searchResponse = $service->Search($searchRequest);
            $item = $searchResponse->getResult()->getResultData()->getItem();
            if ($item !== null) {
                return $item[0]->getResultData()->getTrackingNumber();
            }
        }
    }

}
