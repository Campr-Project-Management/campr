<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161017141407 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_based TINYINT(1) DEFAULT \'1\' NOT NULL, is_baseline TINYINT(1) NOT NULL, INDEX IDX_6EA9A146727ACA70 (parent_id), INDEX IDX_6EA9A146166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, calendar_id INT DEFAULT NULL, type INT NOT NULL, working INT NOT NULL, INDEX IDX_E5A02990A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE working_time (id INT AUTO_INCREMENT NOT NULL, day_id INT DEFAULT NULL, from_time TIME DEFAULT NULL, to_time TIME DEFAULT NULL, INDEX IDX_31EE2ABF9C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146727ACA70 FOREIGN KEY (parent_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE working_time ADD CONSTRAINT FK_31EE2ABF9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE user CHANGE is_enabled is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_suspended is_suspended TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146727ACA70');
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990A40A2C8');
        $this->addSql('ALTER TABLE working_time DROP FOREIGN KEY FK_31EE2ABF9C24126');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE working_time');
        $this->addSql('ALTER TABLE user CHANGE is_enabled is_enabled TINYINT(1) NOT NULL, CHANGE is_suspended is_suspended TINYINT(1) NOT NULL');
    }
}
