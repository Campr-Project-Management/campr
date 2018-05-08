<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180508002032 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rasci DROP FOREIGN KEY FK_EAA3E345EF2F062C');
        $this->addSql('ALTER TABLE rasci ADD CONSTRAINT FK_EAA3E345EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id) ON DELETE SET NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rasci DROP FOREIGN KEY FK_EAA3E345EF2F062C');
        $this->addSql('ALTER TABLE rasci ADD CONSTRAINT FK_EAA3E345EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
    }
}
