<?php

namespace Evaneos\DatabaseMigration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170523113523 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql('CREATE SCHEMA pokemon');
        $this->addSql('
            CREATE TABLE pokemon.collection
            (
                uuid uuid NOT NULL,
                type character varying(150),
                level integer,
                CONSTRAINT pokedex_pkey PRIMARY KEY (uuid)
            )
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP SCHEMA pokemon CASCADE');
    }
}
