<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170315074509 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_package_label (work_package_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_DB7E2E3BEF2F062C (work_package_id), INDEX IDX_DB7E2E3B33B92F39 (label_id), PRIMARY KEY(work_package_id, label_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_package_label ADD CONSTRAINT FK_DB7E2E3BEF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_label ADD CONSTRAINT FK_DB7E2E3B33B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
        $this->addSql('DROP TABLE workpackage_label');
        $this->addSql('CREATE TABLE work_package_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C730AC755E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_package ADD work_package_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB71010AC05 FOREIGN KEY (work_package_category_id) REFERENCES work_package_category (id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB71010AC05 ON work_package (work_package_category_id)');
        $this->addSql('INSERT INTO `work_package_category` (`id`, `name`)
            VALUES 
            (1, \'label.default\')
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE workpackage_label (workpackage_id INT NOT NULL, label_id INT NOT NULL, INDEX IDX_C38A674CDBD8A2B7 (workpackage_id), INDEX IDX_C38A674C33B92F39 (label_id), PRIMARY KEY(workpackage_id, label_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workpackage_label ADD CONSTRAINT FK_C38A674C33B92F39 FOREIGN KEY (label_id) REFERENCES label (id)');
        $this->addSql('ALTER TABLE workpackage_label ADD CONSTRAINT FK_C38A674CDBD8A2B7 FOREIGN KEY (workpackage_id) REFERENCES work_package (id)');
        $this->addSql('DROP TABLE work_package_label');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB71010AC05');
        $this->addSql('DROP TABLE work_package_category');
        $this->addSql('DROP INDEX IDX_BA3DFB71010AC05 ON work_package');
        $this->addSql('ALTER TABLE work_package DROP work_package_category_id');
    }
}
