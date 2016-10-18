<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentLog
 *
 * @ORM\Table(name="picker_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PickerLogRepository")
 * @ORM\HasLifecycleCallbacks
 */
class PickerLog {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="orderNumber", type="string", length=255)
     */
    private $orderNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     *
     * @return DocumentLog
     */
    public function setOrderNumber($orderNumber) {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string
     */
    public function getOrderNumber() {
        return $this->orderNumber;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return DocumentLog
     */
    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return DocumentLog
     */
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * @ORM\PrePersist 
     */
    public function prePersist() {
        $this->timestamp = new \DateTime();
    }

}
