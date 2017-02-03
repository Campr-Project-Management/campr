<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170131111109 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE label (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, color VARCHAR(6) NOT NULL, INDEX IDX_EA750E8166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workpackage_label (workpackage_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_C38A674CDBD8A2B7 (workpackage_id), INDEX IDX_C38A674C33B92F39 (label_id), PRIMARY KEY(workpackage_id, label_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE label ADD CONSTRAINT FK_EA750E8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE workpackage_label ADD CONSTRAINT FK_C38A674CDBD8A2B7 FOREIGN KEY (workpackage_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE workpackage_label ADD CONSTRAINT FK_C38A674C33B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE workpackage_label DROP FOREIGN KEY FK_C38A674C33B92F39');
        $this->addSql('DROP TABLE label');
        $this->addSql('DROP TABLE workpackage_label');
    }
}
