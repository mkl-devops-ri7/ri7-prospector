<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121160757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prospection DROP CONSTRAINT fk_47ebd1b0d32632e8');
        $this->addSql('DROP INDEX idx_47ebd1b0d32632e8');
        $this->addSql('ALTER TABLE prospection ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE prospection RENAME COLUMN _user_id TO user_id');
        $this->addSql('ALTER TABLE prospection ADD CONSTRAINT FK_47EBD1B0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_47EBD1B0A76ED395 ON prospection (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE prospection DROP CONSTRAINT FK_47EBD1B0A76ED395');
        $this->addSql('DROP INDEX IDX_47EBD1B0A76ED395');
        $this->addSql('ALTER TABLE prospection DROP name');
        $this->addSql('ALTER TABLE prospection RENAME COLUMN user_id TO _user_id');
        $this->addSql('ALTER TABLE prospection ADD CONSTRAINT fk_47ebd1b0d32632e8 FOREIGN KEY (_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_47ebd1b0d32632e8 ON prospection (_user_id)');
    }
}
