<?php

namespace AppBundle\Entity;

class ProductDetail {

    private $description;
    private $images;

    public function getDescription() {
        return $this->description;
    }

    public function getImages() {
        return $this->images;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setImages($images) {
        $this->images = $images;
        return $this;
    }

}
