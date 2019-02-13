<?php

namespace Keros\Tools;


use Keros\Entities\Core\Gender;

class GenderBuilder
{
    /**
     * @param Gender $gender
     * @return string
     */
    public function getStringGender(Gender $gender): string
    {
        if ($gender->getLabel() == 'H')
            return 'Monsieur';
        elseif ($gender->getLabel() == 'F')
            return 'Madame';
        return '';
    }
}