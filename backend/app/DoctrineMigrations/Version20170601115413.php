<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170601115413 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8389C3D7DE12AB56 ON opportunity (created_by)');
        $this->addSql('ALTER TABLE risk ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7906D541DE12AB56 ON risk (created_by)');
        $this->addSql('ALTER TABLE measure DROP FOREIGN KEY FK_80071925235B6D1');
        $this->addSql('ALTER TABLE measure DROP FOREIGN KEY FK_800719259A34590F');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_80071925235B6D1 FOREIGN KEY (risk_id) REFERENCES risk (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_800719259A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE measure_comment DROP FOREIGN KEY FK_F0F86145DA37D00');
        $this->addSql('ALTER TABLE measure_comment ADD CONSTRAINT FK_F0F86145DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE measure DROP FOREIGN KEY FK_80071925235B6D1');
        $this->addSql('ALTER TABLE measure DROP FOREIGN KEY FK_800719259A34590F');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_80071925235B6D1 FOREIGN KEY (risk_id) REFERENCES risk (id)');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_800719259A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE measure_comment DROP FOREIGN KEY FK_F0F86145DA37D00');
        $this->addSql('ALTER TABLE measure_comment ADD CONSTRAINT FK_F0F86145DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id)');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7DE12AB56');
        $this->addSql('DROP INDEX IDX_8389C3D7DE12AB56 ON opportunity');
        $this->addSql('ALTER TABLE opportunity DROP created_by');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541DE12AB56');
        $this->addSql('DROP INDEX IDX_7906D541DE12AB56 ON risk');
        $this->addSql('ALTER TABLE risk DROP created_by');
    }
}
