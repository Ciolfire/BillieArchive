<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260514013132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_note_character (character_note_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_AD302D8E4F488868 (character_note_id), INDEX IDX_AD302D8E1136BE75 (character_id), PRIMARY KEY (character_note_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE character_note_character ADD CONSTRAINT FK_AD302D8E4F488868 FOREIGN KEY (character_note_id) REFERENCES character_note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_note_character ADD CONSTRAINT FK_AD302D8E1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_note_character DROP FOREIGN KEY FK_AD302D8E4F488868');
        $this->addSql('ALTER TABLE character_note_character DROP FOREIGN KEY FK_AD302D8E1136BE75');
        $this->addSql('DROP TABLE character_note_character');
    }
}
