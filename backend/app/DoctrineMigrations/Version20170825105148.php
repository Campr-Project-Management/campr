<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170825105148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE status_report (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, user_id INT DEFAULT NULL, information LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, INDEX IDX_7965DFC9166D1F9C (project_id), INDEX IDX_7965DFC9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE status_report ADD CONSTRAINT FK_7965DFC9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE status_report ADD CONSTRAINT FK_7965DFC9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('INSERT INTO `todo_status` (`id`, `name`)
            VALUES 
            (1, \'todo_status.initiated\'),
            (2, \'todo_status.ongoing\'),
            (3, \'todo_status.finished\'),
            (4, \'todo_status.on_hold\'),
            (5, \'todo_status.discontinued\')
        ');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7F3F9A59A');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7F3F9A59A FOREIGN KEY (color_status_id) REFERENCES color_status (id) ON DELETE SET NULL');
        $this->addSql('DELETE FROM `color_status`');
        $this->addSql('INSERT INTO `color_status` (`id`, `name`, `color`, `sequence`, `description`)
            VALUES 
            (1, \'color_status.not_started\', \'#c87369\', 0, NULL),
            (2, \'color_status.in_progress\', \'#ccba54\', 1, NULL),
            (3, \'color_status.finished\', \'#5fc3a5\', 2, NULL)
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE status_report');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7F3F9A59A');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7F3F9A59A FOREIGN KEY (color_status_id) REFERENCES color_status (id)');
    }
}
