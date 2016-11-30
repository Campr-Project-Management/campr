<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161031081027 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE file_system (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, driver VARCHAR(255) NOT NULL, name VARCHAR(32) NOT NULL, config LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, INDEX IDX_715A3734166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, file_system_id INT NOT NULL, user_id INT NOT NULL, path VARCHAR(128) NOT NULL, mime_type VARCHAR(128) NOT NULL, file_size BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6A2CA10C5E9A90D3 (file_system_id), INDEX IDX_6A2CA10CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_system ADD CONSTRAINT FK_715A3734166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C5E9A90D3 FOREIGN KEY (file_system_id) REFERENCES file_system (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C5E9A90D3');
        $this->addSql('DROP TABLE file_system');
        $this->addSql('DROP TABLE media');
    }
}
