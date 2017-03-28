<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170327115935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_package_dependency (dependency_id INT NOT NULL, dependant_id INT NOT NULL, INDEX IDX_14FAB8C6C2F67723 (dependency_id), INDEX IDX_14FAB8C6B3D00E54 (dependant_id), PRIMARY KEY(dependency_id, dependant_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_package_dependency ADD CONSTRAINT FK_14FAB8C6C2F67723 FOREIGN KEY (dependency_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_dependency ADD CONSTRAINT FK_14FAB8C6B3D00E54 FOREIGN KEY (dependant_id) REFERENCES work_package (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_package_dependency');
    }
}
