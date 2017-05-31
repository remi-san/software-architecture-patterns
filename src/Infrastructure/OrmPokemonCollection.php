<?php

namespace Evaneos\Archi\Infrastructure;

use Assert\AssertionFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Evaneos\Archi\Domain\Collection\PokemonCollection;
use Evaneos\Archi\Domain\Model\Pokemon;
use Evaneos\Archi\Domain\Model\VO\PokemonId;
use Evaneos\Archi\Domain\Model\VO\PokemonLevel;
use Evaneos\Archi\Domain\Model\VO\PokemonType;

class OrmPokemonCollection implements PokemonCollection
{
    /** @var EntityRepository */
    private $pokemonRepository;

    /** @var EntityManager */
    private $entityManager;

    /**
     * PokemonController constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->pokemonRepository = $entityManager->getRepository(Pokemon::class);
    }

    /**
     * @param PokemonId $id
     *
     * @return Pokemon|object
     */
    public function get(PokemonId $id)
    {
        return $this->pokemonRepository->find((string) $id);
    }

    /**
     * @return Pokemon[]
     */
    public function all()
    {
        return $this->pokemonRepository->findAll();
    }

    /**
     * @param Pokemon $pokemon
     *
     * @return void
     *
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function add(Pokemon $pokemon)
    {
        $this->entityManager->persist($pokemon);
        $this->entityManager->flush();
    }

    /**
     * @param Pokemon $pokemon
     *
     * @return void
     *
     * @throws OptimisticLockException
     */
    public function update(Pokemon $pokemon)
    {
        $this->entityManager->flush();
    }
}
