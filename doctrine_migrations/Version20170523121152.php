<?php

namespace Evaneos\DatabaseMigration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170523121152 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'pikachu', 5)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'carapuce', 8)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'salameche', 3)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'bulbizare', 2)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'chenipan', 15)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'aspicot', 11)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'roucool', 19)");
        $this->addSql("INSERT INTO pokemon.collection (uuid, type, level) VALUES (uuid_generate_v4(), 'rattata', 20)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('TRUNCATE TABLE pokemon.collection');
    }
}
