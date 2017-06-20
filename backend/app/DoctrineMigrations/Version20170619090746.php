<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170619090746 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE decision_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2FB987F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_63D232C85E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_128BF03E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE486BF700BD');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE486BF700BD FOREIGN KEY (status_id) REFERENCES decision_status (id)');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA146BF700BD');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA146BF700BD FOREIGN KEY (status_id) REFERENCES note_status (id)');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A06BF700BD');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A06BF700BD FOREIGN KEY (status_id) REFERENCES todo_status (id)');
        $this->addSql('ALTER TABLE meeting_agenda DROP duration');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE4867433D9C');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE4867433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE distribution_list_meeting DROP FOREIGN KEY FK_A2742F8567433D9C');
        $this->addSql('ALTER TABLE distribution_list_meeting DROP FOREIGN KEY FK_A2742F85DD1F5839');
        $this->addSql('ALTER TABLE distribution_list_meeting ADD CONSTRAINT FK_A2742F8567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE distribution_list_meeting ADD CONSTRAINT FK_A2742F85DD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_meeting DROP FOREIGN KEY FK_94C267702EDEF100');
        $this->addSql('ALTER TABLE media_meeting DROP FOREIGN KEY FK_94C2677067433D9C');
        $this->addSql('ALTER TABLE media_meeting ADD CONSTRAINT FK_94C26770EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_meeting ADD CONSTRAINT FK_94C2677067433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meeting_agenda DROP FOREIGN KEY FK_2C85F93567433D9C');
        $this->addSql('ALTER TABLE meeting_agenda ADD CONSTRAINT FK_2C85F93567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meeting_objective DROP FOREIGN KEY FK_4FBC467767433D9C');
        $this->addSql('ALTER TABLE meeting_objective ADD CONSTRAINT FK_4FBC467767433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF656467433D9C');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF656467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1467433D9C');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A067433D9C');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A067433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE486BF700BD');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE486BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA146BF700BD');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA146BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A06BF700BD');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A06BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('DROP TABLE decision_status');
        $this->addSql('DROP TABLE note_status');
        $this->addSql('DROP TABLE todo_status');
        $this->addSql('ALTER TABLE meeting_agenda ADD duration TIME NOT NULL');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE4867433D9C');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE4867433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE distribution_list_meeting DROP FOREIGN KEY FK_A2742F85DD1F5839');
        $this->addSql('ALTER TABLE distribution_list_meeting DROP FOREIGN KEY FK_A2742F8567433D9C');
        $this->addSql('ALTER TABLE distribution_list_meeting ADD CONSTRAINT FK_A2742F85DD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id)');
        $this->addSql('ALTER TABLE distribution_list_meeting ADD CONSTRAINT FK_A2742F8567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE media_meeting DROP FOREIGN KEY FK_94C26770EA9FDD75');
        $this->addSql('ALTER TABLE media_meeting DROP FOREIGN KEY FK_94C2677067433D9C');
        $this->addSql('ALTER TABLE media_meeting ADD CONSTRAINT FK_94C267702EDEF100 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE media_meeting ADD CONSTRAINT FK_94C2677067433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting_agenda DROP FOREIGN KEY FK_2C85F93567433D9C');
        $this->addSql('ALTER TABLE meeting_agenda ADD CONSTRAINT FK_2C85F93567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting_objective DROP FOREIGN KEY FK_4FBC467767433D9C');
        $this->addSql('ALTER TABLE meeting_objective ADD CONSTRAINT FK_4FBC467767433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF656467433D9C');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF656467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1467433D9C');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A067433D9C');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A067433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
    }
}
