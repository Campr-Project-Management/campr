<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170810105040 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE486BF700BD');
        $this->addSql('DROP TABLE decision_status');
        $this->addSql('DROP INDEX IDX_84ACBE486BF700BD ON decision');
        $this->addSql('ALTER TABLE decision DROP status_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE decision_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_2FB987F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE486BF700BD FOREIGN KEY (status_id) REFERENCES decision_status (id)');
        $this->addSql('CREATE INDEX IDX_84ACBE486BF700BD ON decision (status_id)');
    }
}
