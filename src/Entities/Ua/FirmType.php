<?php


namespace Keros\Entities\Ua;
use JsonSerializable;
use Keros\Tools\Searchable;
/**
 * @Entity
 * @Table(name="ua_firm_type")
 */
class FirmType  implements JsonSerializable, Searchable
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /** @Column(type="string", length=15) */
    protected $label;


    /**
     * Firm_type constructor.
     * @param $label
     */
    public function __construct($label)
    {
        $this->label = $label;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }


}