<?php

namespace Service;

use DAL\Pokemon;
use Repositories\PokemonRepository;

class PokemonService
{
    /**
     * @var array
     */
    private static $types = [
        "Bulbizarre",
        "Herbizarre",
        "Florizarre",
        "Salameche",
        "Reptincel",
        "Dracaufeu",
        "Carapuce",
        "Carabaffe",
        "Tortank",
        "Chenipan",
        "Chrysacier",
        "Papillusion",
        "Aspicot",
        "Coconfort",
        "Dardargnan",
        "Roucool",
        "Roucoups",
        "Roucarnage",
        "Rattata",
        "Rattatac",
        "Piafabec",
        "Rapasdepic",
        "Abo",
        "Arbok",
        "Pikachu",
        "Raichu",
        "Sabelette",
        "Sablaireau",
        "Nidoran?",
        "Nidorina",
        "Nidoqueen",
        "Nidorino",
        "Nidoking",
        "Melofee",
        "Melodelfe",
        "Goupix",
        "Feunard",
        "Rondoudou",
        "Grodoudou",
        "Nosferapti",
        "Nosferalto",
        "Mystherbe",
        "Ortide",
        "Rafflesia",
        "Paras",
        "Parasect",
        "Mimitoss",
        "Aeromite",
        "Taupiqueur",
        "Triopikeur",
        "Miaouss",
        "Persian",
        "Psykokwak",
        "Akwakwak",
        "Ferosinge",
        "Colossinge",
        "Caninos",
        "Arcanin",
        "Ptitard",
        "Tetarte",
        "Tartard",
        "Abra",
        "Kadabra",
        "Alakazam",
        "Machoc",
        "Machopeur",
        "Mackogneur",
        "Chetiflor",
        "Boustiflor",
        "Empiflor",
        "Tentacool",
        "Tentacruel",
        "Racaillou",
        "Gravalanch",
        "Grolem",
        "Ponyta",
        "Galopa",
        "Ramoloss",
        "Flagadoss",
        "Magneti",
        "Magneton",
        "Canarticho",
        "Doduo",
        "Dodrio",
        "Otaria",
        "Lamantine",
        "Tadmorv",
        "Grotadmorv",
        "Kokiyas",
        "Crustabri",
        "Fantominus",
        "Spectrum",
        "Ectoplasma",
        "Onix",
        "Soporifik",
        "Hypnomade",
        "Kraby",
        "Krabboss",
        "Voltorbe",
        "Electrode",
        "Noeunoeuf",
        "Noadkoko",
        "Osselait",
        "Ossatueur",
        "Kicklee",
        "Tygnon",
        "Excelangue",
        "Smogo",
        "Smogogo",
        "Rhinocorne",
        "Rhinoferos",
        "Leveinard",
        "Saquedeneu",
        "Kangourex",
        "Hypotrempe",
        "Hypocean",
        "Poissirene",
        "Poissoroy",
        "Stari",
        "Staross",
        "M. Mime",
        "Insecateur",
        "Lippoutou",
        "Elektek",
        "Magmar",
        "Scarabrute",
        "Tauros",
        "Magicarpe",
        "Leviator",
        "Lokhlass",
        "Metamorph",
        "Evoli",
        "Aquali",
        "Voltali",
        "Pyroli",
        "Porygon",
        "Amonita",
        "Amonistar",
        "Kabuto",
        "Kabutops",
        "Ptera",
        "Ronflex",
        "Artikodin",
        "Electhor",
        "Sulfura",
        "Minidraco",
        "Draco",
        "Dracolosse",
        "Mewtwo",
        "Mew",
    ];


    /**
     * Constructor.
     *
     * @param  PokemonRepository $repo
     *
     * @return void
     */
    public function __construct(PokemonRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Finds all the pokemons.
     *
     * @return array
     */
    public function pokedex() : array
    {
        $data = [];

        foreach ($this->repo->findAll() as $pokemon) {
            $data[] = Pokemon::fromValues(
                $pokemon['uuid'],
                $pokemon['type'],
                $pokemon['level']
            )->toArray();
        }

        return $data;
    }

    /**
     * Finds a pokemon.
     *
     * @param  string $uuid
     *
     * @return mixed
     */
    public function getInformation(string $uuid)
    {
        if (false === ($pokemon = $this->repo->findOne($uuid))) {
            return null;
        } else {
            return Pokemon::fromValues(
                $pokemon['uuid'],
                $pokemon['type'],
                $pokemon['level']
            );
        }
    }

    /**
     * Captures a pokemon.
     *
     * @param  array $data
     *
     * @return Pokemon
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function capture(array $data) : Pokemon
    {
        if (empty($data['type']) || empty($data['level'])) {
            throw new \InvalidArgumentException('type and level are required.');
        }

        if (!in_array($data['type'], self::$types)) {
            throw new \InvalidArgumentException("type doesn't exist.");
        }

        if (!($data['level'] > 0 && $data['level'] < 31)) {
            throw new \InvalidArgumentException('level must be between 1 and 30.');
        }

        $pokemon = Pokemon::new($data['type'], $data['level']);

        if (false === $this->repo->capture($pokemon)) {
            throw new \Exception('The given pokemon could not be captured.');
        } else {
            return $pokemon;
        }
    }

    /**
     * Checks if a pokemon exists.
     *
     * @param  string $uuid
     *
     * @return bool
     */
    public function exists(string $uuid) : bool
    {
        return $this->repo->exists($uuid);
    }

    /**
     * Evolves a pokemon.
     *
     * @param  Pokemon $pokemon
     *
     * @return Pokemon
     *
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function evolve(Pokemon $pokemon) : Pokemon
    {
        if (!in_array($pokemon->getLevel(), [7, 15])) {
            throw new \InvalidArgumentException('to evolve a pokemon, he should be level 7 or 15.');
        }

        if (false === $this->repo->evolve($pokemon)) {
            throw new \Exception('The given pokemon has not been evolved.');
        } else {
            return Pokemon::fromValues($pokemon->getId(), $pokemon->getType(), $pokemon->getLevel() + 1);
        }
    }
}
