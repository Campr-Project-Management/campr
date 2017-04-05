<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170405143150 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package_status ADD project_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('UPDATE work_package_status SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL ');
        $this->addSql('ALTER TABLE work_package_status ADD CONSTRAINT FK_640F3F0C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_640F3F0C166D1F9C ON work_package_status (project_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package_status DROP FOREIGN KEY FK_640F3F0C166D1F9C');
        $this->addSql('DROP INDEX IDX_640F3F0C166D1F9C ON work_package_status');
        $this->addSql('ALTER TABLE work_package_status DROP project_id, DROP created_at, DROP updated_at');
    }
}
