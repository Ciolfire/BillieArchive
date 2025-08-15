<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250814002624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE released_at released_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE character_note CHANGE assigned_at assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE item ADD is_shared TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE note CHANGE assigned_at assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE status_effect ADD item_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status_effect ADD CONSTRAINT FK_B2A39BF126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_B2A39BF126F525E ON status_effect (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE released_at released_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE character_note CHANGE assigned_at assigned_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE item DROP is_shared');
        $this->addSql('ALTER TABLE note CHANGE assigned_at assigned_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE status_effect DROP FOREIGN KEY FK_B2A39BF126F525E');
        $this->addSql('DROP INDEX IDX_B2A39BF126F525E ON status_effect');
        $this->addSql('ALTER TABLE status_effect DROP item_id');
    }
}
