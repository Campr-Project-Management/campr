<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161018084432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignment (id INT AUTO_INCREMENT NOT NULL, work_package_id INT NOT NULL, work_package_project_work_cost_type_id INT NOT NULL, percent_work_complete INT DEFAULT 0 NOT NULL, milestone INT NOT NULL, confirmed TINYINT(1) DEFAULT \'0\' NOT NULL, started_at DATETIME DEFAULT NULL, finished_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_30C544BAEF2F062C (work_package_id), INDEX IDX_30C544BA8E25B2F5 (work_package_project_work_cost_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timephase (id INT AUTO_INCREMENT NOT NULL, assignment_id INT DEFAULT NULL, type INT NOT NULL, unit INT NOT NULL, value VARCHAR(128) NOT NULL, started_at DATETIME DEFAULT NULL, finished_at DATETIME DEFAULT NULL, INDEX IDX_4125AFFDD19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BAEF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA8E25B2F5 FOREIGN KEY (work_package_project_work_cost_type_id) REFERENCES work_package_project_work_cost_type (id)');
        $this->addSql('ALTER TABLE timephase ADD CONSTRAINT FK_4125AFFDD19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id)');
        $this->addSql('ALTER TABLE work_package ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB7166D1F9C ON work_package (project_id)');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type ADD calendar_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD is_generic TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_inactive TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_enterprise TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_cost_resource TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_budget TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type ADD CONSTRAINT FK_912BB2C2A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_912BB2C25E237E06 ON work_package_project_work_cost_type (name)');
        $this->addSql('CREATE INDEX IDX_912BB2C2A40A2C8 ON work_package_project_work_cost_type (calendar_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE timephase DROP FOREIGN KEY FK_4125AFFDD19302F8');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE timephase');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7166D1F9C');
        $this->addSql('DROP INDEX IDX_BA3DFB7166D1F9C ON work_package');
        $this->addSql('ALTER TABLE work_package DROP project_id');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type DROP FOREIGN KEY FK_912BB2C2A40A2C8');
        $this->addSql('DROP INDEX UNIQ_912BB2C25E237E06 ON work_package_project_work_cost_type');
        $this->addSql('DROP INDEX IDX_912BB2C2A40A2C8 ON work_package_project_work_cost_type');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type DROP calendar_id, DROP name, DROP is_generic, DROP is_inactive, DROP is_enterprise, DROP is_cost_resource, DROP is_budget');
    }
}
