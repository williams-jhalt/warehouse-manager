<?php

namespace AppBundle\Entity;

class Product {

    private $itemNumber;
    private $name;
    private $price;
    private $stockQuantity;
    private $committedQuantity;
    private $binLocation;
    private $detail;

    public function getItemNumber() {
        return $this->itemNumber;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getStockQuantity() {
        return $this->stockQuantity;
    }

    public function getCommittedQuantity() {
        return $this->committedQuantity;
    }

    public function getBinLocation() {
        return $this->binLocation;
    }

    public function setItemNumber($itemNumber) {
        $this->itemNumber = $itemNumber;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function setStockQuantity($stockQuantity) {
        $this->stockQuantity = $stockQuantity;
        return $this;
    }

    public function setCommittedQuantity($committedQuantity) {
        $this->committedQuantity = $committedQuantity;
        return $this;
    }

    public function setBinLocation($binLocation) {
        $this->binLocation = $binLocation;
        return $this;
    }
    
    public function getDetail() {
        return $this->detail;
    }

    public function setDetail(ProductDetail $detail) {
        $this->detail = $detail;
        return $this;
    }



}
