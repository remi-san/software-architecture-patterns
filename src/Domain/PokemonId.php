<?php

namespace Evaneos\Archi\Domain;

use Ramsey\Uuid\Uuid;

class PokemonId
{
    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function create()
    {
        return new self(Uuid::uuid4());
    }

    public function getId()
    {
        return $this->id;
    }
}