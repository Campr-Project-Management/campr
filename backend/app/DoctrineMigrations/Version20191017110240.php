<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191017110240 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA146BF700BD');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE note_status');
        $this->addSql('DROP TABLE IF EXISTS team_slug');
        $this->addSql('ALTER TABLE risk_strategy DROP FOREIGN KEY FK_F26F0682166D1F9C');
        $this->addSql('DROP INDEX IDX_F26F0682166D1F9C ON risk_strategy');
        $this->addSql('ALTER TABLE risk_strategy DROP project_id');
        $this->addSql('ALTER TABLE work_package_status DROP FOREIGN KEY FK_640F3F0C166D1F9C');
        $this->addSql('DROP INDEX IDX_640F3F0C166D1F9C ON work_package_status');
        $this->addSql('ALTER TABLE work_package_status DROP project_id');
        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651C166D1F9C');
        $this->addSql('DROP INDEX IDX_7B00651C166D1F9C ON status');
        $this->addSql('ALTER TABLE status DROP project_id');
        $this->addSql('ALTER TABLE subteam_member CHANGE is_lead lead TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE project_status ADD code VARCHAR(255) NOT NULL, CHANGE sequence sequence INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6CA48E5677153098 ON project_status (code)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, meeting_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, show_in_status_report TINYINT(1) DEFAULT \'0\' NOT NULL, date DATE DEFAULT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CFBDFA14166D1F9C (project_id), INDEX IDX_CFBDFA1467433D9C (meeting_id), INDEX IDX_CFBDFA14385A88B7 (responsibility_id), INDEX IDX_CFBDFA146BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_63D232C85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_slug (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_497C6F19989D9B62 (slug), INDEX IDX_497C6F19296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA146BF700BD FOREIGN KEY (status_id) REFERENCES note_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_slug ADD CONSTRAINT FK_497C6F19296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_6CA48E5677153098 ON project_status');
        $this->addSql('ALTER TABLE project_status DROP code, CHANGE sequence sequence INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE risk_strategy ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE risk_strategy ADD CONSTRAINT FK_F26F0682166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_F26F0682166D1F9C ON risk_strategy (project_id)');
        $this->addSql('ALTER TABLE status ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_7B00651C166D1F9C ON status (project_id)');
        $this->addSql('ALTER TABLE subteam_member CHANGE lead is_lead TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE work_package_status ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package_status ADD CONSTRAINT FK_640F3F0C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_640F3F0C166D1F9C ON work_package_status (project_id)');
    }
}
