<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171221025654 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE risk_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D5416BF700BD');
        $this->addSql('DROP INDEX IDX_7906D5416BF700BD ON risk');
        $this->addSql('UPDATE risk SET status_id = null');
        $this->addSql('ALTER TABLE risk CHANGE status_id risk_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541B61C5537 FOREIGN KEY (risk_status_id) REFERENCES risk_status (id)');
        $this->addSql('CREATE INDEX IDX_7906D541B61C5537 ON risk (risk_status_id)');

        // this is safe because we're doing replace into and we're specifying ids
        $this->addSql('SET foreign_key_checks=0');

        $riskStatuses = [
            'label.not_entered',
            'label.entered',
        ];

        foreach ($riskStatuses as $key => $riskStatus) {
            $this->addSql(
                'REPLACE INTO risk_status SET id = :id, name = :name',
                [
                    'id' => $key + 1,
                    'name' => $riskStatus,
                ]
            );
        }

        $riskStrategies = [
            'label.avoid',
            'label.transfer',
            'label.mitigate',
            'label.accept',
        ];

        foreach ($riskStrategies as $key => $riskStrategy) {
            $this->addSql(
                'REPLACE INTO risk_strategy SET id = :id, sequence = :sequence, name = :name',
                [
                    'id' => $key + 1,
                    'sequence' => $key,
                    'name' => $riskStrategy,
                ]
            );
        }

        $opportunityStatuses = [
            'label.not_taken',
            'label.taken',
        ];

        foreach ($opportunityStatuses as $key => $opportunityStatus) {
            $this->addSql(
                'REPLACE INTO opportunity_status SET id = :id, name = :name, project_id = null',
                [
                    'id' => $key,
                    'name' => $opportunityStatus,
                ]
            );
        }

        $opportunityStrategies = [
            'label.exploit',
            'label.share',
            'label.enhance',
            'label.ignore',
        ];

        foreach ($opportunityStrategies as $key => $opportunityStrategy) {
            $this->addSql(
                'REPLACE INTO opportunity_strategy SET id = :id, name = :name, project_id = null',
                [
                    'id' => $key,
                    'name' => $opportunityStrategy,
                ]
            );
        }

        $units = [
            'label.days',
            'label.weeks',
            'label.months',
        ];

        foreach ($units as $key => $unit) {
            $this->addSql(
                'REPLACE INTO unit SET id = :id, project_id = null, sequence = :sequence, name = :name, created_at = NOW(), updated_at = NOW()',
                [
                    'id' => $key + 1,
                    'sequence' => $key,
                    'name' => $unit,
                ]
            );
        }

        $this->addSql('SET foreign_key_checks=1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541B61C5537');
        $this->addSql('DROP TABLE risk_status');
        $this->addSql('DROP INDEX IDX_7906D541B61C5537 ON risk');
        $this->addSql('ALTER TABLE risk ADD status_id INT DEFAULT NULL, DROP risk_status_id');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D5416BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_7906D5416BF700BD ON risk (status_id)');
    }
}
