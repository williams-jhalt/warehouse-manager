<?php

namespace AppBundle\Service;

class ConnectShipService {
    
    /**
     * @var \SoapClient
     */
    private $client;
    
    public function __construct($wsdl_url) {
        
        $this->client = new \SoapClient($wsdl_url, array('soap_version' => SOAP_1_2));
                
    }    
    
    public function getTrackingNumber($ucc) {
        
        $carriers = $this->client->ListCarriers();
        
        foreach ($carriers->result->resultData->item as $carrier) {
        
            $response = $this->client->Search(['carrier' => $carrier->symbol, 'filters' => ['consigneeReference' => $ucc], 'searchVoided' => "nonvoided"]);
            
            if (!isset($response->result->resultData->item)) {
                continue;
            }
            
            $item = $response->result->resultData->item;

            if (is_array($item)) {
                foreach ($item as $t) {
                    if ($item->resultData->voided == "true") {
                        continue;
                    }
                    return $item[0]->resultData->trackingNumber;
                }
            } else {
                return $item->resultData->trackingNumber;
            }
            
        }
                
    }
    
}