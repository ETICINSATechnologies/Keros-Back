<?php

namespace Keros\Entities\Core;

use JsonSerializable;
use Keros\Tools\Searchable;

/**
 * @Entity
 * @Table(name="core_ticket")
 */
class Ticket implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Member")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $user;

    /** @Column(type="string", length=64) */
    protected $title;

    /** @Column(type="string", length=64) */
    protected $message;

    /** @Column(type="string", length=64) */
    protected $type;

    /** @Column(type="string", length=64) */
    protected $status;


    /**
     *  constructor.
     * @param $user
     * @param $title
     * @param $message
     * @param $type
     * @param $status
     */
    public function __construct($user, $title, $message, $type, $status)
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->status = $status;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser(),
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => $this->getType(),
            'status' => $this->getStatus()
        ];
    }

    public static function getSearchFields(): array
    {
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
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
    public function setCountry($status): void
    {
        $this->status = $status;
    }
}