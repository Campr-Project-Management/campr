<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170424144629 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project_user_project_role (project_user_id INT NOT NULL, project_role_id INT NOT NULL, INDEX IDX_DAE7624F3170DFF0 (project_user_id), INDEX IDX_DAE7624F401D2EC9 (project_role_id), PRIMARY KEY(project_user_id, project_role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_user_project_department (project_user_id INT NOT NULL, project_department_id INT NOT NULL, INDEX IDX_66B3B0CA3170DFF0 (project_user_id), INDEX IDX_66B3B0CA7A1162D9 (project_department_id), PRIMARY KEY(project_user_id, project_department_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_user_project_role ADD CONSTRAINT FK_DAE7624F3170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_user_project_role ADD CONSTRAINT FK_DAE7624F401D2EC9 FOREIGN KEY (project_role_id) REFERENCES project_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_user_project_department ADD CONSTRAINT FK_66B3B0CA3170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_user_project_department ADD CONSTRAINT FK_66B3B0CA7A1162D9 FOREIGN KEY (project_department_id) REFERENCES project_department (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E51401D2EC9');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E517A1162D9');
        $this->addSql('DROP INDEX IDX_B4021E51401D2EC9 ON project_user');
        $this->addSql('DROP INDEX IDX_B4021E517A1162D9 ON project_user');
        $this->addSql('ALTER TABLE project_user DROP project_role_id, DROP project_department_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE project_user_project_role');
        $this->addSql('DROP TABLE project_user_project_department');
        $this->addSql('ALTER TABLE project_user ADD project_role_id INT DEFAULT NULL, ADD project_department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E51401D2EC9 FOREIGN KEY (project_role_id) REFERENCES project_role (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E517A1162D9 FOREIGN KEY (project_department_id) REFERENCES project_department (id)');
        $this->addSql('CREATE INDEX IDX_B4021E51401D2EC9 ON project_user (project_role_id)');
        $this->addSql('CREATE INDEX IDX_B4021E517A1162D9 ON project_user (project_department_id)');
    }
}
