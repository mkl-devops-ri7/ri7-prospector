<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117101358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE prospection_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE prospection (id INT NOT NULL, contact_id INT NOT NULL, _user_id INT NOT NULL, status VARCHAR(20) NOT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47EBD1B0E7A1254A ON prospection (contact_id)');
        $this->addSql('CREATE INDEX IDX_47EBD1B0D32632E8 ON prospection (_user_id)');
        $this->addSql('COMMENT ON COLUMN prospection.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prospection ADD CONSTRAINT FK_47EBD1B0E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prospection ADD CONSTRAINT FK_47EBD1B0D32632E8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action ADD prospection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92CE4F4C9 FOREIGN KEY (prospection_id) REFERENCES prospection (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_47CC8C92CE4F4C9 ON action (prospection_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C92CE4F4C9');
        $this->addSql('DROP SEQUENCE prospection_id_seq CASCADE');
        $this->addSql('ALTER TABLE prospection DROP CONSTRAINT FK_47EBD1B0E7A1254A');
        $this->addSql('ALTER TABLE prospection DROP CONSTRAINT FK_47EBD1B0D32632E8');
        $this->addSql('DROP TABLE prospection');
        $this->addSql('DROP INDEX IDX_47CC8C92CE4F4C9');
        $this->addSql('ALTER TABLE action DROP prospection_id');
    }
}
