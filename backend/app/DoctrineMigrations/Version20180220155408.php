<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180220155408 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subteam ADD department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subteam ADD CONSTRAINT FK_1D55D494AE80F5DF FOREIGN KEY (department_id) REFERENCES project_department (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_1D55D494AE80F5DF ON subteam (department_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subteam DROP FOREIGN KEY FK_1D55D494AE80F5DF');
        $this->addSql('DROP INDEX IDX_1D55D494AE80F5DF ON subteam');
        $this->addSql('ALTER TABLE subteam DROP department_id');
    }
}
