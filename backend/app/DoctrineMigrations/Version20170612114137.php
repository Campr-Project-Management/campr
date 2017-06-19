<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170612114137 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE meeting_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_95DD34145E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting ADD meeting_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E1393E269397 FOREIGN KEY (meeting_category_id) REFERENCES meeting_category (id)');
        $this->addSql('CREATE INDEX IDX_F515E1393E269397 ON meeting (meeting_category_id)');
        $this->addSql('CREATE TABLE meeting_objective (id INT AUTO_INCREMENT NOT NULL, meeting_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_4FBC467767433D9C (meeting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting_objective ADD CONSTRAINT FK_4FBC467767433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting DROP objectives');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E1393E269397');
        $this->addSql('DROP TABLE meeting_category');
        $this->addSql('DROP INDEX IDX_F515E1393E269397 ON meeting');
        $this->addSql('ALTER TABLE meeting DROP meeting_category_id');
        $this->addSql('DROP TABLE meeting_objective');
        $this->addSql('ALTER TABLE meeting ADD objectives LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
