<?php

namespace AppBundle\Service;

class OrderService {
    
    private $redis;
    
    /**
     * @var ErpOneConnectorService
     */
    private $erp;
    
    public function __construct(ErpOneConnectorService $erp, $redis) {        
        $this->redis = $redis;
        $this->erp = $erp;
    }
    
}

