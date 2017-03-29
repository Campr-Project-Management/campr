<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170328075424 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE project_deliverable (id INT AUTO_INCREMENT NOT NULL, contract_id INT DEFAULT NULL, project_id INT DEFAULT NULL, description LONGTEXT NOT NULL, sequence INT DEFAULT 0 NOT NULL, INDEX IDX_69B253BA2576E0FD (contract_id), INDEX IDX_69B253BA166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_limitation (id INT AUTO_INCREMENT NOT NULL, contract_id INT DEFAULT NULL, project_id INT DEFAULT NULL, description LONGTEXT NOT NULL, sequence INT DEFAULT 0 NOT NULL, INDEX IDX_4A2291602576E0FD (contract_id), INDEX IDX_4A229160166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_objective (id INT AUTO_INCREMENT NOT NULL, contract_id INT DEFAULT NULL, project_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sequence INT DEFAULT 0 NOT NULL, INDEX IDX_A8AAB4D62576E0FD (contract_id), INDEX IDX_A8AAB4D6166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_deliverable ADD CONSTRAINT FK_69B253BA2576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE project_deliverable ADD CONSTRAINT FK_69B253BA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_limitation ADD CONSTRAINT FK_4A2291602576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE project_limitation ADD CONSTRAINT FK_4A229160166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_objective ADD CONSTRAINT FK_A8AAB4D62576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE project_objective ADD CONSTRAINT FK_A8AAB4D6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE project_deliverable');
        $this->addSql('DROP TABLE project_limitation');
        $this->addSql('DROP TABLE project_objective');
    }
}
