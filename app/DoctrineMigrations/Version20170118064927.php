<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170118064927 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE12F7FB51');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE783E3463');
        $this->addSql('DROP INDEX IDX_2FB3D0EE12F7FB51 ON project');
        $this->addSql('DROP INDEX IDX_2FB3D0EE783E3463 ON project');
        $this->addSql('ALTER TABLE project DROP sponsor_id, DROP manager_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project ADD sponsor_id INT DEFAULT NULL, ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE12F7FB51 FOREIGN KEY (sponsor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE12F7FB51 ON project (sponsor_id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE783E3463 ON project (manager_id)');
    }
}
