<?php

namespace Evaneos\Archi\Query\Model;

class QueryPokemon
{
   /** @var string */
    private $id;

    /** @var string */
    private $type;

    /** @var int */
    private $level;

    /**
     * Pokemon constructor.
     *
     * @param string $id
     * @param string $type
     * @param int    $level
     */
    public function __construct(
        $id,
        $type,
        $level
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'level' => $this->level
        ];
    }
}
