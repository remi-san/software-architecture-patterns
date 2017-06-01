<?php

namespace Evaneos\Archi\ReadModel\Model;

/**
 * @Entity
 * @Table(name="pokemon.collection")
 */
class Pokemon
{
    /**
     * @Id
     * @Column(type="pokemon-id", name="uuid")
     */
    private $id;

    /**
     * @Column(type="pokemon-type")
     */
    private $type;

    /**
     * @Column(type="pokemon-level")
     */
    private $level;

    /**
     * Pokemon constructor.
     *
     * @param $id
     * @param $type
     * @param $level
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
     * @return integer
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
            'id' => (string) $this->id,
            'type' => (string) $this->type,
            'level' => $this->level
        ];
    }
}
