<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180927100836 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_invite ADD inviter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570EB79F4F04 FOREIGN KEY (inviter_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B1F9570EB79F4F04 ON team_invite (inviter_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_invite DROP FOREIGN KEY FK_B1F9570EB79F4F04');
        $this->addSql('DROP INDEX IDX_B1F9570EB79F4F04 ON team_invite');
        $this->addSql('ALTER TABLE team_invite DROP inviter_id');
    }
}
