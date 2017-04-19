<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170418215154 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package ADD phase_id INT DEFAULT NULL, ADD milestone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB799091188 FOREIGN KEY (phase_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB74B3E2EDA FOREIGN KEY (milestone_id) REFERENCES work_package (id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB799091188 ON work_package (phase_id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB74B3E2EDA ON work_package (milestone_id)');
        $this->addSql('update work_package wp set wp.phase_id = (select wp2.id from (select * from work_package) wp2 where wp.parent_id = wp2.id and wp2.type = 0)');
        $this->addSql('update work_package wp set wp.milestone_id = (select wp2.id from (select * from work_package) wp2 where wp.parent_id = wp2.id and wp2.type = 1);');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB799091188');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB74B3E2EDA');
        $this->addSql('DROP INDEX IDX_BA3DFB799091188 ON work_package');
        $this->addSql('DROP INDEX IDX_BA3DFB74B3E2EDA ON work_package');
        $this->addSql('ALTER TABLE work_package DROP phase_id, DROP milestone_id');
    }
}
