<?php

namespace DAL;

use Ramsey\Uuid\Uuid;

final class Pokemon
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $level;


    /**
     * Constructor.
     *
     * @param  string $uuid
     * @param  string $type
     * @param  int    $level
     *
     * @return void
     */
    private function __construct(string $uuid, string $type, int $level)
    {
        $this->uuid = $uuid;
        $this->type = $type;
        $this->level = $level;
    }

    /**
     * Returns the current instance to array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
            'uuid' => $this->uuid,
            'type' => $this->type,
            'level' => $this->level,
        ];
    }

    /**
     * Returns a Pokemon.
     *
     * @param  string $uuid
     * @param  string $type
     * @param  int    $level
     *
     * @return Pokemon
     */
    public static function fromValues(string $uuid, string $type, int $level) : Pokemon
    {
        return new static($uuid, $type, $level);
    }

    /**
     * Generates a new Pokemon.
     *
     * @param  string $type
     * @param  int    $level
     *
     * @return Pokemon
     */
    public static function new(string $type, int $level) : Pokemon
    {
        return new static(Uuid::uuid4(), $type, $level);
    }

    /**
     * Get Id.
     *
     * @return string
     */
    public function getId() : string
    {
        return $this->uuid;
    }

    /**
     * Get Type.
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Get Level.
     *
     * @return int
     */
    public function getLevel() : int
    {
        return $this->level;
    }
}
