<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170731125659 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A0385A88B7;");
        $this->addSql("ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A0385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id) ON DELETE SET NULL;");
        $this->addSql("ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48385A88B7;");
        $this->addSql("ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id) ON DELETE SET NULL;");
        $this->addSql("ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14385A88B7;");
        $this->addSql("ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id) ON DELETE SET NULL;");
        $this->addSql("ALTER TABLE raci DROP FOREIGN KEY FK_D3D9F784A76ED395;");
        $this->addSql("ALTER TABLE raci ADD CONSTRAINT FK_D3D9F784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL;");
        $this->addSql("ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF6564A76ED395;");
        $this->addSql("ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF6564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48385A88B7;");
        $this->addSql("ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id);");
        $this->addSql("ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF6564A76ED395;");
        $this->addSql("ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF6564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);");
        $this->addSql("ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14385A88B7;");
        $this->addSql("ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id);");
        $this->addSql("ALTER TABLE raci DROP FOREIGN KEY FK_D3D9F784A76ED395;");
        $this->addSql("ALTER TABLE raci ADD CONSTRAINT FK_D3D9F784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id);");
        $this->addSql("ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A0385A88B7;");
        $this->addSql("ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A0385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id);");
    }
}
