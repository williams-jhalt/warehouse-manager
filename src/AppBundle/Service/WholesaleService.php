<?php

namespace AppBundle\Service;

class WholesaleService {
    
    private $wholesale_api_url = "http://wholesale.williams-trading.com/rest";
    private $redis;
    
    public function __construct($redis) {
        $this->redis = $redis;
    }
    
    public function getProductData($sku) {
        
        if ($this->redis->exists($sku . ":productData")) {
            $data = $this->redis->get($sku . ":productData");
        } else {
            $data = file_get_contents($this->wholesale_api_url . "/products/" . $sku . "?format=json");
            $this->redis->set($sku . ":productData", $data);
        }
        
        return json_decode($data)->product;
        
    }
    
    public function getProductImageData($sku) {
        
        if ($this->redis->exists($sku . ":productImageData")) {
            $data = $this->redis->get($sku . ":productImageData");
        } else {
            $data = file_get_contents($this->wholesale_api_url . "/product-images/" . $sku . "?format=json");
            $this->redis->set($sku . ":productImageData", $data);
        }
        
        return json_decode($data)->images;
        
    }
    
}