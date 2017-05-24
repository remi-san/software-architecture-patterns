<?php

namespace Evaneos\Archi\Controllers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PokemonController
{
    /** @var Connection */
    private $connection;

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
     * PokemonController constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws DBALException
     */
    public function pokedex(Request $request)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection';
        $query = $this->connection->query($sql);

        return new JsonResponse([$query->fetchAll()]);
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     *
     * @throws \InvalidArgumentException
     * @throws DBALException
     */
    public function getInformation($uuid)
    {
        $sql = 'SELECT uuid, type, level FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();

        $pokemon = $query->fetch();

        if ($pokemon === false) {
            return new JsonResponse(new \stdClass(), 404);
        }

        return new JsonResponse($pokemon);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function capture(Request $request)
    {
        $type = $request->get('type');
        $level = (int) $request->get('level');

        if (in_array($type, self::$types) && ($level > 0 && $level < 31)) {
            $uuid = (string) Uuid::uuid4();

            $sql = 'INSERT INTO pokemon.collection (uuid, type, level) VALUES (:uuid, :type, :level)';
            $query = $this->connection->prepare($sql);

            $query->bindValue('uuid', $uuid);
            $query->bindValue('type', $type);
            $query->bindValue('level', $level);
            $query->execute();

            return new JsonResponse([
                'uuid' => $uuid,
                'type' => $type,
                'level' => $level
            ]);
        } else {
            return new JsonResponse([], 400);
        }
    }

    /**
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function evolve($uuid)
    {
        $sql = 'SELECT COUNT(uuid) FROM pokemon.collection WHERE uuid = :uuid';
        $query = $this->connection->prepare($sql);

        $query->bindValue('uuid', $uuid);
        $query->execute();

        $result = $query->fetch();

        if ($result['count']) {
            $sql = 'SELECT type, level FROM pokemon.collection WHERE uuid = :uuid';
            $query = $this->connection->prepare($sql);

            $query->bindValue('uuid', $uuid);
            $query->execute();

            $result = $query->fetch();

            if (in_array($result['level'], [7, 15])) {
                $sql = 'UPDATE pokemon.collection SET level = level + 1 WHERE uuid = :uuid';
                $query = $this->connection->prepare($sql);

                $query->bindValue('uuid', $uuid);
                $query->execute();

                return new JsonResponse([
                    'uuid' => $uuid,
                    'type' => $result['type'],
                    'level' => $result['level'] + 1,
                ], 200);
            } else {
                return new JsonResponse([], 403);
            }
        } else {
            return new JsonResponse([], 404);
        }
    }
}
