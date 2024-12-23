<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223011940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE released_at released_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE character_access ADD importance SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE character_note CHANGE assigned_at assigned_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE note CHANGE assigned_at assigned_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE released_at released_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE character_access DROP importance');
        $this->addSql('ALTER TABLE character_note CHANGE assigned_at assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE note CHANGE assigned_at assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
