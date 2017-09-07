<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170906134220 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE close_down_action (id INT AUTO_INCREMENT NOT NULL, project_close_down_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_18A90D158E744ACD (project_close_down_id), INDEX IDX_18A90D15385A88B7 (responsibility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_objective (id INT AUTO_INCREMENT NOT NULL, project_close_down_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, INDEX IDX_6B963A858E744ACD (project_close_down_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id INT AUTO_INCREMENT NOT NULL, project_close_down_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, INDEX IDX_F87474F38E744ACD (project_close_down_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_close_down (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, overall_impression LONGTEXT DEFAULT NULL, performance_schedule LONGTEXT DEFAULT NULL, organization_context LONGTEXT DEFAULT NULL, project_management LONGTEXT DEFAULT NULL, frozen TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_70212FF2166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE close_down_action ADD CONSTRAINT FK_18A90D158E744ACD FOREIGN KEY (project_close_down_id) REFERENCES project_close_down (id)');
        $this->addSql('ALTER TABLE close_down_action ADD CONSTRAINT FK_18A90D15385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE evaluation_objective ADD CONSTRAINT FK_6B963A858E744ACD FOREIGN KEY (project_close_down_id) REFERENCES project_close_down (id)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F38E744ACD FOREIGN KEY (project_close_down_id) REFERENCES project_close_down (id)');
        $this->addSql('ALTER TABLE project_close_down ADD CONSTRAINT FK_70212FF2166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE close_down_action CHANGE description description LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE close_down_action DROP FOREIGN KEY FK_18A90D158E744ACD');
        $this->addSql('ALTER TABLE evaluation_objective DROP FOREIGN KEY FK_6B963A858E744ACD');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F38E744ACD');
        $this->addSql('DROP TABLE close_down_action');
        $this->addSql('DROP TABLE evaluation_objective');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE project_close_down');
        $this->addSql('ALTER TABLE close_down_action CHANGE description description LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
