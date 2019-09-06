<?php

namespace Keros\Entities\Treso;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="treso_payment_slip_document_type")
 */
class PaymentSlipDocumentType implements JsonSerializable
{

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string", length=255)
     */
    protected $location;

    /**
     * PaymentSlipDocumentType constructor.
     * @param int $id
     * @param string $location
     */
    public function __construct(int $id, string $location)
    {
        $this->id = $id;
        $this->location = $location;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'location' => $this->getLocation()
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

}