<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180925080932 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD enabled TINYINT(1) DEFAULT \'0\' NOT NULL, ADD suspended TINYINT(1) DEFAULT \'0\' NOT NULL, DROP is_enabled, DROP is_suspended, CHANGE password password VARCHAR(128) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_suspended TINYINT(1) DEFAULT \'0\' NOT NULL, DROP enabled, DROP suspended, CHANGE password password VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
