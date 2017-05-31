<?php

namespace Evaneos\Archi\Domain\Model\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;

class PokemonId
{
    /** @var string */
    private $uuid;

    /**
     * PokemonId constructor.
     *
     * @param string $uuid
     *
     * @throws AssertionFailedException
     */
    public function __construct($uuid)
    {
        Assertion::uuid($uuid);

        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->uuid;
    }

    /**
     * @return PokemonId
     *
     * @throws AssertionFailedException
     */
    public static function generate()
    {
        return new self((string) Uuid::uuid4());
    }
}
