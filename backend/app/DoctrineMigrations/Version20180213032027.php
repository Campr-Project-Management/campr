<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180213032027 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE info_user');
        $this->addSql('ALTER TABLE info ADD meeting_id INT DEFAULT NULL, ADD responsibility_id INT DEFAULT NULL, CHANGE expiry_date due_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB89315767433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB893157385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_CB89315767433D9C ON info (meeting_id)');
        $this->addSql('CREATE INDEX IDX_CB893157385A88B7 ON info (responsibility_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE info_user (info_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D4F804C75D8BC1F8 (info_id), INDEX IDX_D4F804C7A76ED395 (user_id), PRIMARY KEY(info_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE info_user ADD CONSTRAINT FK_D4F804C75D8BC1F8 FOREIGN KEY (info_id) REFERENCES info (id)');
        $this->addSql('ALTER TABLE info_user ADD CONSTRAINT FK_D4F804C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE info DROP FOREIGN KEY FK_CB89315767433D9C');
        $this->addSql('ALTER TABLE info DROP FOREIGN KEY FK_CB893157385A88B7');
        $this->addSql('DROP INDEX IDX_CB89315767433D9C ON info');
        $this->addSql('DROP INDEX IDX_CB893157385A88B7 ON info');
        $this->addSql('ALTER TABLE info CHANGE due_date expiry_date DATE NOT NULL, DROP meeting_id, DROP responsibility_id');
    }
}
