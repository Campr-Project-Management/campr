<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170601094950 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity CHANGE cost_savings cost_savings NUMERIC(9, 2) DEFAULT NULL, CHANGE budget budget NUMERIC(9, 2) DEFAULT NULL, CHANGE time_savings time_savings NUMERIC(9, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE risk CHANGE cost cost NUMERIC(9, 2) DEFAULT NULL, CHANGE budget budget NUMERIC(9, 2) DEFAULT NULL, CHANGE delay delay NUMERIC(9, 2) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity CHANGE cost_savings cost_savings VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE budget budget VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE time_savings time_savings VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE risk CHANGE cost cost VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE budget budget VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE delay delay VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
