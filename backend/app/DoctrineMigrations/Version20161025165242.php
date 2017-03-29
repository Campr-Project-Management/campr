<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161025165242 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE assignment (id INT AUTO_INCREMENT NOT NULL, work_package_id INT NOT NULL, work_package_project_work_cost_type_id INT NOT NULL, percent_work_complete INT DEFAULT 0 NOT NULL, milestone INT NOT NULL, confirmed TINYINT(1) DEFAULT \'0\' NOT NULL, started_at DATETIME DEFAULT NULL, finished_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_30C544BAEF2F062C (work_package_id), INDEX IDX_30C544BA8E25B2F5 (work_package_project_work_cost_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_based TINYINT(1) DEFAULT \'1\' NOT NULL, is_baseline TINYINT(1) NOT NULL, INDEX IDX_6EA9A146727ACA70 (parent_id), INDEX IDX_6EA9A146166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(6) NOT NULL, sequence INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, schedule_id INT DEFAULT NULL, meeting_name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, location VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F9AFB5EB166D1F9C (project_id), INDEX IDX_F9AFB5EBA40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication_participant (communication_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FCFF88551C2D1E0C (communication_id), INDEX IDX_FCFF8855A76ED395 (user_id), PRIMARY KEY(communication_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4FBF094F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, calendar_id INT DEFAULT NULL, type INT NOT NULL, working INT NOT NULL, INDEX IDX_E5A02990A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, meeting_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, show_in_status_report TINYINT(1) DEFAULT \'0\' NOT NULL, date DATE DEFAULT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_84ACBE48166D1F9C (project_id), INDEX IDX_84ACBE4867433D9C (meeting_id), INDEX IDX_84ACBE48385A88B7 (responsibility_id), INDEX IDX_84ACBE486BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D8698A76B548B0F (path), INDEX IDX_D8698A76166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_meeting (document_id INT NOT NULL, meeting_id INT NOT NULL, INDEX IDX_517A47E5C33F7837 (document_id), INDEX IDX_517A47E567433D9C (meeting_id), PRIMARY KEY(document_id, meeting_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE impact (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_C409C0075E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, class VARCHAR(255) NOT NULL, obj_id INT NOT NULL, old_value LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', new_value LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, INDEX IDX_8F3F68C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, date DATE NOT NULL, start TIME NOT NULL, end TIME NOT NULL, objectives LONGTEXT NOT NULL, INDEX IDX_F515E139166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting_agenda (id INT AUTO_INCREMENT NOT NULL, meeting_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, topic VARCHAR(255) NOT NULL, start TIME NOT NULL, end TIME NOT NULL, duration TIME NOT NULL, INDEX IDX_2C85F93567433D9C (meeting_id), INDEX IDX_2C85F935385A88B7 (responsibility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meeting_participant (id INT AUTO_INCREMENT NOT NULL, meeting_id INT DEFAULT NULL, user_id INT DEFAULT NULL, remark VARCHAR(255) DEFAULT NULL, is_present TINYINT(1) DEFAULT \'0\' NOT NULL, is_excused TINYINT(1) DEFAULT \'0\' NOT NULL, in_distribution_list TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_FBFF656467433D9C (meeting_id), INDEX IDX_FBFF6564A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, meeting_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, show_in_status_report TINYINT(1) DEFAULT \'0\' NOT NULL, date DATE DEFAULT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CFBDFA14166D1F9C (project_id), INDEX IDX_CFBDFA1467433D9C (meeting_id), INDEX IDX_CFBDFA14385A88B7 (responsibility_id), INDEX IDX_CFBDFA146BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portfolio (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A9ED10625E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, sponsor_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, company_id INT DEFAULT NULL, project_complexity_id INT DEFAULT NULL, project_category_id INT DEFAULT NULL, project_scope_id INT DEFAULT NULL, project_status_id INT DEFAULT NULL, portfolio_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, number VARCHAR(128) NOT NULL, status_updated_at DATETIME DEFAULT NULL, approved_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2FB3D0EE96901F54 (number), INDEX IDX_2FB3D0EE12F7FB51 (sponsor_id), INDEX IDX_2FB3D0EE783E3463 (manager_id), INDEX IDX_2FB3D0EE979B1AD6 (company_id), INDEX IDX_2FB3D0EEE566340 (project_complexity_id), INDEX IDX_2FB3D0EEDA896A19 (project_category_id), INDEX IDX_2FB3D0EE5565D2EA (project_scope_id), INDEX IDX_2FB3D0EE7ACB456A (project_status_id), INDEX IDX_2FB3D0EEB96B5643 (portfolio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_category (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3B02921A5E237E06 (name), INDEX IDX_3B02921A166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_complexity (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4043BDA95E237E06 (name), INDEX IDX_4043BDA9166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_cost_type (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C1FA0B415E237E06 (name), INDEX IDX_C1FA0B41166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_department (id INT AUTO_INCREMENT NOT NULL, project_work_cost_type_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, rate NUMERIC(10, 2) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D5BB9AB8B5830A79 (project_work_cost_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_module (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, module VARCHAR(32) NOT NULL, is_enabled TINYINT(1) NOT NULL, is_required TINYINT(1) DEFAULT NULL, is_print TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1B80CD62166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, is_lead TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6EF842725E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_scope (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_13FA5C4D5E237E06 (name), INDEX IDX_13FA5C4D166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_status (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6CA48E565E237E06 (name), INDEX IDX_6CA48E56166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FD716E075E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, project_id INT DEFAULT NULL, project_category_id INT DEFAULT NULL, project_role_id INT DEFAULT NULL, project_department_id INT DEFAULT NULL, project_team_id INT DEFAULT NULL, show_in_resources TINYINT(1) DEFAULT \'1\' NOT NULL, show_in_raci TINYINT(1) DEFAULT NULL, show_in_org TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B4021E51A76ED395 (user_id), INDEX IDX_B4021E51166D1F9C (project_id), INDEX IDX_B4021E51DA896A19 (project_category_id), INDEX IDX_B4021E51401D2EC9 (project_role_id), INDEX IDX_B4021E517A1162D9 (project_department_id), INDEX IDX_B4021E51BF72D4CB (project_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_work_cost_type (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5B79D2615E237E06 (name), INDEX IDX_5B79D261166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE raci (id INT AUTO_INCREMENT NOT NULL, work_package_id INT DEFAULT NULL, user_id INT DEFAULT NULL, data VARCHAR(255) NOT NULL, INDEX IDX_D3D9F784EF2F062C (work_package_id), INDEX IDX_D3D9F784A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE risk (id INT AUTO_INCREMENT NOT NULL, impact_id INT DEFAULT NULL, risk_strategy_id INT DEFAULT NULL, risk_category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, cost VARCHAR(255) NOT NULL, budget VARCHAR(255) NOT NULL, delay VARCHAR(255) NOT NULL, priority VARCHAR(255) NOT NULL, measure LONGTEXT NOT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_7906D541D128BC9B (impact_id), INDEX IDX_7906D541FA7D163 (risk_strategy_id), INDEX IDX_7906D541C82B95B3 (risk_category_id), INDEX IDX_7906D541A76ED395 (user_id), INDEX IDX_7906D5416BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE risk_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_E0655AAE5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE risk_strategy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_F26F06825E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5A3811FB5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7B00651C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE timephase (id INT AUTO_INCREMENT NOT NULL, assignment_id INT DEFAULT NULL, type INT NOT NULL, unit INT NOT NULL, value VARCHAR(128) NOT NULL, started_at DATETIME DEFAULT NULL, finished_at DATETIME DEFAULT NULL, INDEX IDX_4125AFFDD19302F8 (assignment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, meeting_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, status_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, show_in_status_report TINYINT(1) DEFAULT \'0\' NOT NULL, date DATE DEFAULT NULL, due_date DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5A0EB6A0166D1F9C (project_id), INDEX IDX_5A0EB6A067433D9C (meeting_id), INDEX IDX_5A0EB6A0385A88B7 (responsibility_id), INDEX IDX_5A0EB6A06BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_DCBB0C535E237E06 (name), INDEX IDX_DCBB0C53166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(128) NOT NULL, email VARCHAR(128) NOT NULL, phone VARCHAR(128) DEFAULT NULL, first_name VARCHAR(128) NOT NULL, last_name VARCHAR(128) NOT NULL, password VARCHAR(128) NOT NULL, salt VARCHAR(32) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', is_enabled TINYINT(1) DEFAULT \'0\' NOT NULL, is_suspended TINYINT(1) DEFAULT \'0\' NOT NULL, activation_token VARCHAR(32) DEFAULT NULL, activation_token_created_at DATETIME DEFAULT NULL, reset_password_token VARCHAR(32) DEFAULT NULL, reset_password_token_created_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, activated_at DATETIME DEFAULT NULL, UNIQUE INDEX username_unique (username), UNIQUE INDEX email_unique (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE working_time (id INT AUTO_INCREMENT NOT NULL, day_id INT DEFAULT NULL, from_time TIME DEFAULT NULL, to_time TIME DEFAULT NULL, INDEX IDX_31EE2ABF9C24126 (day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_package (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, color_status_id INT DEFAULT NULL, project_id INT DEFAULT NULL, responsibility_id INT DEFAULT NULL, puid VARCHAR(128) NOT NULL, name VARCHAR(255) NOT NULL, progress INT DEFAULT 0 NOT NULL, scheduled_start_at DATE DEFAULT NULL, scheduled_finish_at DATE DEFAULT NULL, forecast_start_at DATE DEFAULT NULL, forecast_finish_at DATE DEFAULT NULL, actual_start_at DATE DEFAULT NULL, actual_finish_at DATE DEFAULT NULL, content LONGTEXT DEFAULT NULL, results LONGTEXT DEFAULT NULL, is_key_milestone TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BA3DFB7727ACA70 (parent_id), INDEX IDX_BA3DFB7F3F9A59A (color_status_id), INDEX IDX_BA3DFB7166D1F9C (project_id), INDEX IDX_BA3DFB7385A88B7 (responsibility_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_package_project_work_cost_type (id INT AUTO_INCREMENT NOT NULL, work_package_id INT NOT NULL, project_work_cost_type_id INT NOT NULL, calendar_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, base NUMERIC(10, 2) DEFAULT NULL, change_value NUMERIC(10, 2) DEFAULT NULL, actual NUMERIC(10, 2) DEFAULT NULL, remaining NUMERIC(10, 2) DEFAULT NULL, forecast NUMERIC(10, 2) DEFAULT NULL, is_generic TINYINT(1) DEFAULT \'0\' NOT NULL, is_inactive TINYINT(1) DEFAULT \'0\' NOT NULL, is_enterprise TINYINT(1) DEFAULT \'0\' NOT NULL, is_cost_resource TINYINT(1) DEFAULT \'0\' NOT NULL, is_budget TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_912BB2C25E237E06 (name), INDEX IDX_912BB2C2EF2F062C (work_package_id), INDEX IDX_912BB2C2B5830A79 (project_work_cost_type_id), INDEX IDX_912BB2C2A40A2C8 (calendar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE automailer (id INT AUTO_INCREMENT NOT NULL, from_email VARCHAR(255) NOT NULL, from_name VARCHAR(255) NOT NULL, to_email VARCHAR(255) NOT NULL, subject LONGTEXT NOT NULL, body LONGTEXT NOT NULL, alt_body LONGTEXT NOT NULL, swift_message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, sent_at DATETIME DEFAULT NULL, started_sending_at DATETIME DEFAULT NULL, is_html TINYINT(1) NOT NULL, is_sending TINYINT(1) DEFAULT NULL, is_sent TINYINT(1) DEFAULT NULL, is_failed TINYINT(1) DEFAULT NULL, INDEX find_next (is_sent, is_failed, is_sending, created_at), INDEX recover_sending (is_sending, started_sending_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BAEF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BA8E25B2F5 FOREIGN KEY (work_package_project_work_cost_type_id) REFERENCES work_package_project_work_cost_type (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146727ACA70 FOREIGN KEY (parent_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EBA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE communication_participant ADD CONSTRAINT FK_FCFF88551C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE communication_participant ADD CONSTRAINT FK_FCFF8855A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE4867433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE48385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE486BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE document_meeting ADD CONSTRAINT FK_517A47E5C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document_meeting ADD CONSTRAINT FK_517A47E567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE log ADD CONSTRAINT FK_8F3F68C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E139166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE meeting_agenda ADD CONSTRAINT FK_2C85F93567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting_agenda ADD CONSTRAINT FK_2C85F935385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF656467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE meeting_participant ADD CONSTRAINT FK_FBFF6564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1467433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA146BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE12F7FB51 FOREIGN KEY (sponsor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEE566340 FOREIGN KEY (project_complexity_id) REFERENCES project_complexity (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEDA896A19 FOREIGN KEY (project_category_id) REFERENCES project_category (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE5565D2EA FOREIGN KEY (project_scope_id) REFERENCES project_scope (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7ACB456A FOREIGN KEY (project_status_id) REFERENCES project_status (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEB96B5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE project_category ADD CONSTRAINT FK_3B02921A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_complexity ADD CONSTRAINT FK_4043BDA9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_cost_type ADD CONSTRAINT FK_C1FA0B41166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_department ADD CONSTRAINT FK_D5BB9AB8B5830A79 FOREIGN KEY (project_work_cost_type_id) REFERENCES project_work_cost_type (id)');
        $this->addSql('ALTER TABLE project_module ADD CONSTRAINT FK_1B80CD62166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_scope ADD CONSTRAINT FK_13FA5C4D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_status ADD CONSTRAINT FK_6CA48E56166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E51A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E51166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E51DA896A19 FOREIGN KEY (project_category_id) REFERENCES project_category (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E51401D2EC9 FOREIGN KEY (project_role_id) REFERENCES project_role (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E517A1162D9 FOREIGN KEY (project_department_id) REFERENCES project_department (id)');
        $this->addSql('ALTER TABLE project_user ADD CONSTRAINT FK_B4021E51BF72D4CB FOREIGN KEY (project_team_id) REFERENCES project_team (id)');
        $this->addSql('ALTER TABLE project_work_cost_type ADD CONSTRAINT FK_5B79D261166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE raci ADD CONSTRAINT FK_D3D9F784EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE raci ADD CONSTRAINT FK_D3D9F784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541D128BC9B FOREIGN KEY (impact_id) REFERENCES impact (id)');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541FA7D163 FOREIGN KEY (risk_strategy_id) REFERENCES risk_strategy (id)');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541C82B95B3 FOREIGN KEY (risk_category_id) REFERENCES risk_category (id)');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D541A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE risk ADD CONSTRAINT FK_7906D5416BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE timephase ADD CONSTRAINT FK_4125AFFDD19302F8 FOREIGN KEY (assignment_id) REFERENCES assignment (id)');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A0166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A067433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A0385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE todo ADD CONSTRAINT FK_5A0EB6A06BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE unit ADD CONSTRAINT FK_DCBB0C53166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE working_time ADD CONSTRAINT FK_31EE2ABF9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7727ACA70 FOREIGN KEY (parent_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7F3F9A59A FOREIGN KEY (color_status_id) REFERENCES color_status (id)');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7385A88B7 FOREIGN KEY (responsibility_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type ADD CONSTRAINT FK_912BB2C2EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type ADD CONSTRAINT FK_912BB2C2B5830A79 FOREIGN KEY (project_work_cost_type_id) REFERENCES project_work_cost_type (id)');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type ADD CONSTRAINT FK_912BB2C2A40A2C8 FOREIGN KEY (calendar_id) REFERENCES calendar (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE timephase DROP FOREIGN KEY FK_4125AFFDD19302F8');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146727ACA70');
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990A40A2C8');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type DROP FOREIGN KEY FK_912BB2C2A40A2C8');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7F3F9A59A');
        $this->addSql('ALTER TABLE communication_participant DROP FOREIGN KEY FK_FCFF88551C2D1E0C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE979B1AD6');
        $this->addSql('ALTER TABLE working_time DROP FOREIGN KEY FK_31EE2ABF9C24126');
        $this->addSql('ALTER TABLE document_meeting DROP FOREIGN KEY FK_517A47E5C33F7837');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541D128BC9B');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE4867433D9C');
        $this->addSql('ALTER TABLE document_meeting DROP FOREIGN KEY FK_517A47E567433D9C');
        $this->addSql('ALTER TABLE meeting_agenda DROP FOREIGN KEY FK_2C85F93567433D9C');
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF656467433D9C');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1467433D9C');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A067433D9C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB96B5643');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146166D1F9C');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EB166D1F9C');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48166D1F9C');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76166D1F9C');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E139166D1F9C');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14166D1F9C');
        $this->addSql('ALTER TABLE project_category DROP FOREIGN KEY FK_3B02921A166D1F9C');
        $this->addSql('ALTER TABLE project_complexity DROP FOREIGN KEY FK_4043BDA9166D1F9C');
        $this->addSql('ALTER TABLE project_cost_type DROP FOREIGN KEY FK_C1FA0B41166D1F9C');
        $this->addSql('ALTER TABLE project_module DROP FOREIGN KEY FK_1B80CD62166D1F9C');
        $this->addSql('ALTER TABLE project_scope DROP FOREIGN KEY FK_13FA5C4D166D1F9C');
        $this->addSql('ALTER TABLE project_status DROP FOREIGN KEY FK_6CA48E56166D1F9C');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E51166D1F9C');
        $this->addSql('ALTER TABLE project_work_cost_type DROP FOREIGN KEY FK_5B79D261166D1F9C');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A0166D1F9C');
        $this->addSql('ALTER TABLE unit DROP FOREIGN KEY FK_DCBB0C53166D1F9C');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7166D1F9C');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEDA896A19');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E51DA896A19');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEE566340');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E517A1162D9');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E51401D2EC9');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE5565D2EA');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7ACB456A');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E51BF72D4CB');
        $this->addSql('ALTER TABLE project_department DROP FOREIGN KEY FK_D5BB9AB8B5830A79');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type DROP FOREIGN KEY FK_912BB2C2B5830A79');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541C82B95B3');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541FA7D163');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EBA40BC2D5');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE486BF700BD');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA146BF700BD');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D5416BF700BD');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A06BF700BD');
        $this->addSql('ALTER TABLE communication_participant DROP FOREIGN KEY FK_FCFF8855A76ED395');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE48385A88B7');
        $this->addSql('ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C5A76ED395');
        $this->addSql('ALTER TABLE meeting_agenda DROP FOREIGN KEY FK_2C85F935385A88B7');
        $this->addSql('ALTER TABLE meeting_participant DROP FOREIGN KEY FK_FBFF6564A76ED395');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14385A88B7');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE12F7FB51');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE783E3463');
        $this->addSql('ALTER TABLE project_user DROP FOREIGN KEY FK_B4021E51A76ED395');
        $this->addSql('ALTER TABLE raci DROP FOREIGN KEY FK_D3D9F784A76ED395');
        $this->addSql('ALTER TABLE risk DROP FOREIGN KEY FK_7906D541A76ED395');
        $this->addSql('ALTER TABLE todo DROP FOREIGN KEY FK_5A0EB6A0385A88B7');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7385A88B7');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BAEF2F062C');
        $this->addSql('ALTER TABLE raci DROP FOREIGN KEY FK_D3D9F784EF2F062C');
        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7727ACA70');
        $this->addSql('ALTER TABLE work_package_project_work_cost_type DROP FOREIGN KEY FK_912BB2C2EF2F062C');
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BA8E25B2F5');
        $this->addSql('DROP TABLE assignment');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE color_status');
        $this->addSql('DROP TABLE communication');
        $this->addSql('DROP TABLE communication_participant');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_meeting');
        $this->addSql('DROP TABLE impact');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE meeting_agenda');
        $this->addSql('DROP TABLE meeting_participant');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE portfolio');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_category');
        $this->addSql('DROP TABLE project_complexity');
        $this->addSql('DROP TABLE project_cost_type');
        $this->addSql('DROP TABLE project_department');
        $this->addSql('DROP TABLE project_module');
        $this->addSql('DROP TABLE project_role');
        $this->addSql('DROP TABLE project_scope');
        $this->addSql('DROP TABLE project_status');
        $this->addSql('DROP TABLE project_team');
        $this->addSql('DROP TABLE project_user');
        $this->addSql('DROP TABLE project_work_cost_type');
        $this->addSql('DROP TABLE raci');
        $this->addSql('DROP TABLE risk');
        $this->addSql('DROP TABLE risk_category');
        $this->addSql('DROP TABLE risk_strategy');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE timephase');
        $this->addSql('DROP TABLE todo');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE working_time');
        $this->addSql('DROP TABLE work_package');
        $this->addSql('DROP TABLE work_package_project_work_cost_type');
        $this->addSql('DROP TABLE automailer');
    }
}
