<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170927003220 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project ADD progress INT DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX puid_project_unique ON work_package');
        $this->addSql('UPDATE work_package SET puid = id');
        $this->addSql('ALTER TABLE work_package CHANGE puid puid INT DEFAULT 0 NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP progress');
        $this->addSql('ALTER TABLE work_package CHANGE puid puid VARCHAR(128) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX puid_project_unique ON work_package (puid, project_id)');
    }
}
