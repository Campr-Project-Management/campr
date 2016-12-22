<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20161221011920 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_meeting RENAME INDEX idx_94c267702edef100 TO IDX_94C26770EA9FDD75');
        $this->addSql('ALTER TABLE team ADD user_id INT DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD enabled TINYINT(1) DEFAULT \'0\' NOT NULL;');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61FA76ED395 ON team (user_id)');
        $this->addSql('CREATE TABLE command_queue_log (id BIGINT AUTO_INCREMENT NOT NULL, command LONGTEXT NOT NULL, queue_count INT DEFAULT 0 NOT NULL, output LONGTEXT DEFAULT NULL, exit_code INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE command_queue_log');
        $this->addSql('ALTER TABLE media_meeting RENAME INDEX idx_94c26770ea9fdd75 TO IDX_94C267702EDEF100');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FA76ED395');
        $this->addSql('DROP INDEX IDX_C4E0A61FA76ED395 ON team');
        $this->addSql('ALTER TABLE team DROP user_id, DROP description, DROP enabled');
    }
}
