<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170523081440 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE measure (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, risk_id INT DEFAULT NULL, opportunity_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, cost INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_80071925A76ED395 (user_id), INDEX IDX_80071925235B6D1 (risk_id), INDEX IDX_800719259A34590F (opportunity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measure_media (measure_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_A6670FDF5DA37D00 (measure_id), INDEX IDX_A6670FDFEA9FDD75 (media_id), PRIMARY KEY(measure_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measure_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, measure_id INT DEFAULT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F0F8614A76ED395 (user_id), INDEX IDX_F0F86145DA37D00 (measure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measure_comment_media (measure_comment_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_15CA1F448008ECCF (measure_comment_id), INDEX IDX_15CA1F44EA9FDD75 (media_id), PRIMARY KEY(measure_comment_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity (id INT AUTO_INCREMENT NOT NULL, opportunity_strategy_id INT DEFAULT NULL, user_id INT DEFAULT NULL, opportunity_status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, impact INT NOT NULL, probability INT NOT NULL, cost_savings VARCHAR(255) NOT NULL, budget VARCHAR(255) NOT NULL, time_savings VARCHAR(255) NOT NULL, priority VARCHAR(255) NOT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8389C3D7DED0E39F (opportunity_strategy_id), INDEX IDX_8389C3D7A76ED395 (user_id), INDEX IDX_8389C3D7EF666483 (opportunity_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_383118785E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity_strategy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3B63A5FE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_80071925A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_80071925235B6D1 FOREIGN KEY (risk_id) REFERENCES risk (id)');
        $this->addSql('ALTER TABLE measure ADD CONSTRAINT FK_800719259A34590F FOREIGN KEY (opportunity_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE measure_media ADD CONSTRAINT FK_A6670FDF5DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE measure_media ADD CONSTRAINT FK_A6670FDFEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE measure_comment ADD CONSTRAINT FK_F0F8614A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE measure_comment ADD CONSTRAINT FK_F0F86145DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id)');
        $this->addSql('ALTER TABLE measure_comment_media ADD CONSTRAINT FK_15CA1F448008ECCF FOREIGN KEY (measure_comment_id) REFERENCES measure_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE measure_comment_media ADD CONSTRAINT FK_15CA1F44EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7DED0E39F FOREIGN KEY (opportunity_strategy_id) REFERENCES opportunity_strategy (id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7EF666483 FOREIGN KEY (opportunity_status_id) REFERENCES opportunity_status (id)');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541D128BC9B');
        $this->addSql('DROP INDEX IDX_7906D541D128BC9B ON risk');
        $this->addSql('ALTER TABLE risk ADD impact INT NOT NULL, ADD probability INT NOT NULL, DROP impact_id, DROP measure');
        $this->addSql('ALTER TABLE opportunity ADD project_id INT DEFAULT NULL, CHANGE cost_savings cost_savings VARCHAR(255) DEFAULT NULL, CHANGE budget budget VARCHAR(255) DEFAULT NULL, CHANGE time_savings time_savings VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_8389C3D7166D1F9C ON opportunity (project_id)');
        $this->addSql('ALTER TABLE risk ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_7906D541166D1F9C ON risk (project_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7166D1F9C');
        $this->addSql('DROP INDEX IDX_8389C3D7166D1F9C ON opportunity');
        $this->addSql('ALTER TABLE opportunity DROP project_id, CHANGE cost_savings cost_savings VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE budget budget VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE time_savings time_savings VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541166D1F9C');
        $this->addSql('DROP INDEX IDX_7906D541166D1F9C ON risk');
        $this->addSql('ALTER TABLE risk DROP project_id');
        $this->addSql('ALTER TABLE measure_media DROP FOREIGN KEY FK_A6670FDF5DA37D00');
        $this->addSql('ALTER TABLE measure_comment DROP FOREIGN KEY FK_F0F86145DA37D00');
        $this->addSql('ALTER TABLE measure_comment_media DROP FOREIGN KEY FK_15CA1F448008ECCF');
        $this->addSql('ALTER TABLE measure DROP FOREIGN KEY FK_800719259A34590F');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7EF666483');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D7DED0E39F');
        $this->addSql('DROP TABLE measure');
        $this->addSql('DROP TABLE measure_media');
        $this->addSql('DROP TABLE measure_comment');
        $this->addSql('DROP TABLE measure_comment_media');
        $this->addSql('DROP TABLE opportunity');
        $this->addSql('DROP TABLE opportunity_status');
        $this->addSql('DROP TABLE opportunity_strategy');
        $this->addSql('ALTER TABLE risk ADD impact_id INT DEFAULT NULL, ADD measure LONGTEXT NOT NULL COLLATE utf8_unicode_ci, DROP impact, DROP probability');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541D128BC9B FOREIGN KEY (impact_id) REFERENCES impact (id)');
        $this->addSql('CREATE INDEX IDX_7906D541D128BC9B ON risk (impact_id)');
    }
}
