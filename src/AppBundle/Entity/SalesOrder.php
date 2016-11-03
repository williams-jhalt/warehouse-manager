<?php

namespace AppBundle\Entity;

class SalesOrder {

    private $orderNumber;
    private $recordSequence;
    private $manifestId;
    private $orderDate;
    private $customerNumber;

    public function getOrderNumber() {
        return $this->orderNumber;
    }

    public function getRecordSequence() {
        return $this->recordSequence;
    }

    public function getManifestId() {
        return $this->manifestId;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getCustomerNumber() {
        return $this->customerNumber;
    }

    public function setOrderNumber($orderNumber) {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function setRecordSequence($recordSequence) {
        $this->recordSequence = $recordSequence;
        return $this;
    }

    public function setManifestId($manifestId) {
        $this->manifestId = $manifestId;
        return $this;
    }

    public function setOrderDate($orderDate) {
        $this->orderDate = $orderDate;
        return $this;
    }

    public function setCustomerNumber($customerNumber) {
        $this->customerNumber = $customerNumber;
        return $this;
    }

}
