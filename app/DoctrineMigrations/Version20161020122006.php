<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161020122006 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package ADD calendar_id INT DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT \'WorkPackage\' NOT NULL');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB7A40A2C8 ON work_package (calendar_id)');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type CHANGE name name VARCHAR(255) DEFAULT \'Resource\' NOT NULL');
        $this->addSql('ALTER TABLE project CHANGE approved_at approved_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type CHANGE project_work_cost_type_id project_work_cost_type_id INT DEFAULT NULL, CHANGE work_package_id work_package_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_912BB2C25E237E06 ON work_package_project_work_cost_type');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type CHANGE is_generic is_generic TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE `change` change_value NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment CHANGE work_package_project_work_cost_type_id work_package_project_work_cost_type_id INT DEFAULT NULL, CHANGE work_package_id work_package_id INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7A40A2C8');
        $this->addSql('DROP INDEX IDX_BA3DFB7A40A2C8 ON work_package');
        $this->addSql('ALTER TABLE work_package DROP calendar_id, CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE project CHANGE approved_at approved_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type CHANGE work_package_id work_package_id INT NOT NULL, CHANGE project_work_cost_type_id project_work_cost_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type CHANGE is_generic is_generic TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE change_value `change` NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment CHANGE work_package_id work_package_id INT NOT NULL, CHANGE work_package_project_work_cost_type_id work_package_project_work_cost_type_id INT NOT NULL');
    }
}
