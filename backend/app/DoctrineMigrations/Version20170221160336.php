<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170221160336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE custom_field (id INT AUTO_INCREMENT NOT NULL, field_name VARCHAR(255) NOT NULL, field_type VARCHAR(255) DEFAULT NULL, class VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE custom_field_value (id INT AUTO_INCREMENT NOT NULL, custom_field_id INT DEFAULT NULL, obj_id INT NOT NULL, value LONGTEXT NOT NULL, INDEX IDX_EC7B05A1E5E0D4 (custom_field_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE custom_field_value ADD CONSTRAINT FK_EC7B05A1E5E0D4 FOREIGN KEY (custom_field_id) REFERENCES custom_field (id)');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type ADD external_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_912BB2C29F75D7B0 ON work_package_project_work_cost_type (external_id)');
        $this->addSql('ALTER TABLE timephase ADD external_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4125AFFD9F75D7B0 ON timephase (external_id)');
        $this->addSql('ALTER TABLE work_package ADD external_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA3DFB79F75D7B0 ON work_package (external_id)');
        $this->addSql('ALTER TABLE assignment ADD external_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_30C544BA9F75D7B0 ON assignment (external_id)');
        $this->addSql('ALTER TABLE calendar ADD external_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6EA9A1469F75D7B0 ON calendar (external_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_field_value DROP FOREIGN KEY FK_EC7B05A1E5E0D4');
        $this->addSql('DROP TABLE custom_field');
        $this->addSql('DROP TABLE custom_field_value');
        $this->addSql('DROP INDEX UNIQ_30C544BA9F75D7B0 ON assignment');
        $this->addSql('ALTER TABLE assignment DROP external_id');
        $this->addSql('DROP INDEX UNIQ_6EA9A1469F75D7B0 ON calendar');
        $this->addSql('ALTER TABLE calendar DROP external_id');
        $this->addSql('DROP INDEX UNIQ_4125AFFD9F75D7B0 ON timephase');
        $this->addSql('ALTER TABLE timephase DROP external_id');
        $this->addSql('DROP INDEX UNIQ_BA3DFB79F75D7B0 ON work_package');
        $this->addSql('ALTER TABLE work_package DROP external_id');
        $this->addSql('DROP INDEX UNIQ_912BB2C29F75D7B0 ON work_package_project_work_cost_type');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type DROP external_id');
    }
}
