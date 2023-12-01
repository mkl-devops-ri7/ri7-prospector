<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231130155827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Database migration for the project';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE action (id INT NOT NULL, prospection_id UUID DEFAULT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, object VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47CC8C92CE4F4C9 ON action (prospection_id)');
        $this->addSql('COMMENT ON COLUMN action.prospection_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN action.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE company (id INT NOT NULL, siret VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, linked_in_profil_url TEXT DEFAULT NULL, name VARCHAR(255) NOT NULL, logo_url VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN company.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL, company_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, job VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, linkedin_profil_url TEXT NOT NULL, phone_number VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C62E638979B1AD6 ON contact (company_id)');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, user_targeted_id INT NOT NULL, type VARCHAR(255) NOT NULL, object VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, read_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CA8392DC69 ON notification (user_targeted_id)');
        $this->addSql('COMMENT ON COLUMN notification.read_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN notification.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prospection (id UUID NOT NULL, contact_id INT DEFAULT NULL, user_id INT NOT NULL, status VARCHAR(255) NOT NULL, comment TEXT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT \'draft\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47EBD1B0E7A1254A ON prospection (contact_id)');
        $this->addSql('CREATE INDEX IDX_47EBD1B0A76ED395 ON prospection (user_id)');
        $this->addSql('COMMENT ON COLUMN prospection.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN prospection.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, linkedin_profil_url TEXT DEFAULT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92CE4F4C9 FOREIGN KEY (prospection_id) REFERENCES prospection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA8392DC69 FOREIGN KEY (user_targeted_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prospection ADD CONSTRAINT FK_47EBD1B0E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prospection ADD CONSTRAINT FK_47EBD1B0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE action_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C92CE4F4C9');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E638979B1AD6');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA8392DC69');
        $this->addSql('ALTER TABLE prospection DROP CONSTRAINT FK_47EBD1B0E7A1254A');
        $this->addSql('ALTER TABLE prospection DROP CONSTRAINT FK_47EBD1B0A76ED395');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE prospection');
        $this->addSql('DROP TABLE "user"');
    }
}
