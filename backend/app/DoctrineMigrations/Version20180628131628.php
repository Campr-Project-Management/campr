<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180628131628 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7F3F9A59A');
        $this->addSql('DROP INDEX IDX_BA3DFB7F3F9A59A ON work_package');
        $this->addSql('ALTER TABLE work_package ADD traffic_light INT DEFAULT 2 NOT NULL, DROP color_status_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package ADD color_status_id INT DEFAULT NULL, DROP traffic_light');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7F3F9A59A FOREIGN KEY (color_status_id) REFERENCES color_status (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_BA3DFB7F3F9A59A ON work_package (color_status_id)');
    }
}
