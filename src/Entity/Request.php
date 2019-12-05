<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="requests")
 **/
class Request implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="Item", inversedBy="requests")
     * @ORM\JoinTable(name="item_has_request")
     */
    private $items;

    /**
     * @ORM\Column(type="string")
     */
    private $deliveryAddress;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string")
     */
    private $payment_type;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="float")
     */
    private $total_value;

    const SOLICITATION_STATUS = [
        'default' => 'wip',
        'done' => 'done'
    ];

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems(Collection $items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param mixed $deliveryAddress
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * @param mixed $payment_type
     */
    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTotalValue()
    {
        return $this->total_value;
    }

    /**
     * @param mixed $total_value
     */
    public function setTotalValue($total_value)
    {
        $this->total_value = $total_value;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->getItems() as $item) {
            $data[] = $item->jsonSerialize();
        }

        return [
            "id" => $this->getId(),
            "client" => $this->getClient(),
            "items" => $data,
            "payment_type" => $this->getPaymentType(),
            "created_at" => $this->getCreatedAt(),
            "address" => $this->getDeliveryAddress(),
            "status" => $this->getStatus(),
            'total_value' => $this->getTotalValue()
        ];
    }
}
