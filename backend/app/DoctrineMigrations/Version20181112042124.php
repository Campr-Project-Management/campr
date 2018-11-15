<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181112042124 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE info ADD distribution_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB893157DD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_CB893157DD1F5839 ON info (distribution_list_id)');
        $this->addSql('ALTER TABLE decision ADD distribution_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48DD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_84ACBE48DD1F5839 ON decision (distribution_list_id)');
        $this->addSql('ALTER TABLE todo ADD distribution_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A0DD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5A0EB6A0DD1F5839 ON todo (distribution_list_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48DD1F5839');
        $this->addSql('DROP INDEX IDX_84ACBE48DD1F5839 ON decision');
        $this->addSql('ALTER TABLE decision DROP distribution_list_id');
        $this->addSql('ALTER TABLE info DROP FOREIGN KEY FK_CB893157DD1F5839');
        $this->addSql('DROP INDEX IDX_CB893157DD1F5839 ON info');
        $this->addSql('ALTER TABLE info DROP distribution_list_id');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A0DD1F5839');
        $this->addSql('DROP INDEX IDX_5A0EB6A0DD1F5839 ON todo');
        $this->addSql('ALTER TABLE todo DROP distribution_list_id');
    }
}
