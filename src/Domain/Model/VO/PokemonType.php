<?php

namespace Evaneos\Archi\Domain\Model\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;

class PokemonType
{
    /**
     * @var array
     */
    private static $evolutions = [
        'pikachu' => ['evolution' => 'raichu', 'minLevel' => 1],
        'raichu' => ['evolution' => null, 'minLevel' => 7],

        'carapuce' => ['evolution' => 'carabaffe', 'minLevel' => 1],
        'carabaffe' => ['evolution' => 'tortank', 'minLevel' => 7],
        'tortank' => ['evolution' => null, 'minLevel' => 15],

        'salameche' => ['evolution' => 'reptincel', 'minLevel' => 1],
        'reptincel' => ['evolution' => 'dracaufeu', 'minLevel' => 7],
        'dracaufeu' => ['evolution' => null, 'minLevel' => 15],

        'bulbizarre' => ['evolution' => 'herbizarre', 'minLevel' => 1],
        'herbizarre' => ['evolution' => 'florizarre', 'minLevel' => 7],
        'florizarre' => ['evolution' => null, 'minLevel' => 15],

        'chenipan' => ['evolution' => 'chrysacier', 'minLevel' => 1],
        'chrysacier' => ['evolution' => 'papillusion', 'minLevel' => 7],
        'papillusion' => ['evolution' => null, 'minLevel' => 15],

        'aspicot' => ['evolution' => 'aspicot', 'minLevel' => 1],
        'coconfort' => ['evolution' => 'dardagnan', 'minLevel' => 7],
        'dardagnan' => ['evolution' => null, 'minLevel' => 15],

        'roucool' => ['evolution' => 'roucoups', 'minLevel' => 1],
        'roucoups' => ['evolution' => 'roucarnage', 'minLevel' => 7],
        'roucarnage' => ['evolution' => null, 'minLevel' => 15],

        'rattata' => ['evolution' => 'rattatac', 'minLevel' => 1],
        'rattatac' => ['evolution' => null, 'minLevel' => 7],
    ];

    /** @var string */
    private $type;

    /** @var PokemonLevel */
    private $minimumLevel;

    /**
     * PokemonType constructor.
     *
     * @param string $type
     *
     * @throws AssertionFailedException
     */
    public function __construct($type)
    {
        Assertion::inArray($type, array_keys(self::$evolutions), 'Pokemon type "' . $type . '" does not exist');

        $this->type = $type;
        $this->minimumLevel = new PokemonLevel(self::$evolutions[$type]['minLevel']);
    }

    /**
     * @return PokemonType|null
     *
     * @throws AssertionFailedException
     */
    public function getEvolution()
    {
        $evolutionType = self::$evolutions[$this->type]['evolution'];

        if ($evolutionType === null) {
            return null;
        }

        return new self($evolutionType);
    }

    /**
     * @return PokemonLevel
     */
    public function getMinimumLevel()
    {
        return $this->minimumLevel;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }
}
