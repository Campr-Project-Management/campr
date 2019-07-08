<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190422111307 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' != $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE project_department_member (id INT AUTO_INCREMENT NOT NULL, project_department_id INT DEFAULT NULL, project_user_id INT DEFAULT NULL, lead TINYINT(1) NOT NULL DEFAULT 0, INDEX IDX_502D59B77A1162D9 (project_department_id), INDEX IDX_502D59B73170DFF0 (project_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE project_department_member ADD CONSTRAINT FK_502D59B77A1162D9 FOREIGN KEY (project_department_id) REFERENCES project_department (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE project_department_member ADD CONSTRAINT FK_502D59B73170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'INSERT INTO project_department_member(project_department_id, project_user_id) SELECT project_department_id, project_user_id from project_user_project_department'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' != $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE project_department_member');
    }
}
