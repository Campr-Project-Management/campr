<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170412051847 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cost ADD unit_id INT DEFAULT NULL, DROP unit');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FCF8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('CREATE INDEX IDX_182694FCF8BD700D ON cost (unit_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cost DROP FOREIGN KEY FK_182694FCF8BD700D');
        $this->addSql('DROP INDEX IDX_182694FCF8BD700D ON cost');
        $this->addSql('ALTER TABLE cost ADD unit NUMERIC(9, 2) DEFAULT NULL, DROP unit_id');
    }
}
