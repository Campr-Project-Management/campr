<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180604080432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE info DROP FOREIGN KEY FK_CB893157C687BD80');
        $this->addSql('DROP TABLE info_status');
        $this->addSql('DROP INDEX IDX_CB893157C687BD80 ON info');
        $this->addSql('ALTER TABLE info DROP info_status_id, CHANGE due_date expires_at DATE DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE info_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, color VARCHAR(7) DEFAULT \'#ffffff\' NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE info ADD info_status_id INT NOT NULL, CHANGE expires_at due_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB893157C687BD80 FOREIGN KEY (info_status_id) REFERENCES info_status (id)');
        $this->addSql('CREATE INDEX IDX_CB893157C687BD80 ON info (info_status_id)');
    }
}
