<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170224113503 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_invite DROP FOREIGN KEY FK_B1F9570E296CD8AE');
        $this->addSql('ALTER TABLE team_invite DROP FOREIGN KEY FK_B1F9570EA76ED395');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_slug DROP FOREIGN KEY FK_497C6F19296CD8AE');
        $this->addSql('ALTER TABLE team_slug ADD CONSTRAINT FK_497C6F19296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE team_invite DROP FOREIGN KEY FK_B1F9570E296CD8AE');
        $this->addSql('ALTER TABLE team_invite DROP FOREIGN KEY FK_B1F9570EA76ED395');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570E296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team_slug DROP FOREIGN KEY FK_497C6F19296CD8AE');
        $this->addSql('ALTER TABLE team_slug ADD CONSTRAINT FK_497C6F19296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }
}
