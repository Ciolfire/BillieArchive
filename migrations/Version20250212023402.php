<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212023402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mage_spell (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, action SMALLINT NOT NULL, is_vulgar TINYINT(1) NOT NULL, cost VARCHAR(255) NOT NULL, rules LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_64EE38037B02D7EA (homebrew_for_id), INDEX IDX_64EE380316A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE mage_spell_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX mage_spell_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE mage_spell ADD CONSTRAINT FK_64EE38037B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE mage_spell ADD CONSTRAINT FK_64EE380316A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_spell DROP FOREIGN KEY FK_64EE38037B02D7EA');
        $this->addSql('ALTER TABLE mage_spell DROP FOREIGN KEY FK_64EE380316A2B381');
        $this->addSql('DROP TABLE mage_spell');
        $this->addSql('DROP TABLE mage_spell_translation');
    }
}
