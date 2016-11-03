<?php

namespace AppBundle\Entity;

class Carton {

    private $orderNumber;
    private $recordSequence;
    private $ucc;
    private $cartonNumber;
    private $trackingNumber;

    public function getOrderNumber() {
        return $this->orderNumber;
    }

    public function getRecordSequence() {
        return $this->recordSequence;
    }

    public function getUcc() {
        return $this->ucc;
    }

    public function getCartonNumber() {
        return $this->cartonNumber;
    }

    public function setOrderNumber($orderNumber) {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function setRecordSequence($recordSequence) {
        $this->recordSequence = $recordSequence;
        return $this;
    }

    public function setUcc($ucc) {
        $this->ucc = $ucc;
        return $this;
    }

    public function setCartonNumber($cartonNumber) {
        $this->cartonNumber = $cartonNumber;
        return $this;
    }

    public function getTrackingNumber() {
        return $this->trackingNumber;
    }

    public function setTrackingNumber($trackingNumber) {
        $this->trackingNumber = $trackingNumber;
        return $this;
    }

}
