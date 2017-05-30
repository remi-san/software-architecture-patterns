<?php

namespace Evaneos\Archi\Domain;

use Evaneos\Archi\Exceptions\UnknownPokemonType;

class PokemonType
{
    private $type;

    public function __construct($type)
    {
        $this->setType($type);
    }

    public function getType()
    {
        return $this->type;
    }

    private function setType($type)
    {
        if (!$this->typeIsKnown($type)) {
            throw new UnknownPokemonType(
                sprintf('Unknown Pokemon type "%s".', $type)
            );
        }
        $this->type = $type;
    }

    private function typeIsKnown($type)
    {
        $knownType = [
            'aspicot',
            'bulbizare', // WTF?
            'bulbizarre',
            'carapuce',
            'chenipan',
            'florizarre',
            'herbizarre',
            'pikachu',
            'rattata',
            'roucool',
            'salameche',
        ];

        return in_array($type, $knownType);
    }
}



