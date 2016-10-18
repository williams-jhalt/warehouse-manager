<?php

namespace AppBundle\Entity;

class Product {

    private $itemNumber;
    private $name;
    private $description;
    private $price;
    private $stockQuantity;
    private $binLocation;

    function getItemNumber() {
        return $this->itemNumber;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getPrice() {
        return $this->price;
    }

    function getStockQuantity() {
        return $this->stockQuantity;
    }

    function getBinLocation() {
        return $this->binLocation;
    }

    function setItemNumber($itemNumber) {
        $this->itemNumber = $itemNumber;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setStockQuantity($stockQuantity) {
        $this->stockQuantity = $stockQuantity;
    }

    function setBinLocation($binLocation) {
        $this->binLocation = $binLocation;
    }

}
