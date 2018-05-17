<?php

namespace Keros\Entities\Cat;

/**
 * Class Cat.
 * @package Keros\Entities\Cat
 */
class Cat
{
    public $id;
    public $name;
    public $height;

    public function __construct(int $id = null, string $name = null, float $height = null)
    {
        if($id != null) $this->id = $id;
        if($name != null) $this->name = $name;
        if($height != null) $this->height = $height;
    }
}