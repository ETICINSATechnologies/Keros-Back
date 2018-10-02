<?php

namespace Keros\Entities\Core;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Keros\Error\KerosException;
use Keros\Tools\Searchable;

/**
 * @Entity
 * @Table(name="core_user")
 */
class User implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="string", length=50) */
    protected $username;

    /** @Column(type="string", length=100) */
    protected $password;

    /** @Column(type="datetime") */
    protected $lastConnectedAt;

    /** @Column(type="datetime") */
    protected $createdAt;

    /** @Column(type="boolean", length=50) */
    protected $disabled;

    /** @Column(type="datetime", length=50) */
    protected $expiresAt;

    /**
     * User constructor.
     * @param $username
     * @param $password
     * @param $lastConnected
     * @param $createdAt
     * @param $disabled
     * @param $expiresAt
     */
    public function __construct($username, $password, $lastConnected, $createdAt, $disabled, $expiresAt)
    {
        $this->username = $username;
        $this->password = $password;
        $this->lastConnectedAt = $lastConnected;
        $this->createdAt = $createdAt;
        $this->disabled = $disabled;
        $this->expiresAt = $expiresAt;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'lastConnectedAt' => $this->getLastConnectedAt(),
            'createdAt' => $this->getCreatedAt(),
            'disabled' => $this->getDisabled(),
            'expiresAt' => $this->getExpiresAt(),
        ];
    }

    public static function getSearchFields(): array {
        return ['label'];
    }

    // Getters and setters
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getLastConnectedAt()
    {
        return $this->lastConnectedAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @param $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @param mixed $lastConnectedAt
     */
    public function setLastConnectedAt($lastConnectedAt): void
    {
        $this->lastConnectedAt = $lastConnectedAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param mixed $disabled
     */
    public function setDisabled($disabled): void
    {
        $this->disabled = $disabled;
    }

    /**
     * @param mixed $expiresAt
     */
    public function setExpiresAt($expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
}