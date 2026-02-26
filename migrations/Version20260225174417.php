<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225174417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rite (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, level SMALLINT NOT NULL, cost VARCHAR(255) NOT NULL, is_contested TINYINT(1) NOT NULL, contested_text VARCHAR(255) DEFAULT NULL, page SMALLINT DEFAULT NULL, action SMALLINT NOT NULL, renown_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_D1FF6E818B777BD8 (renown_id), INDEX IDX_D1FF6E817B02D7EA (homebrew_for_id), INDEX IDX_D1FF6E8116A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE rite_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX rite_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rite ADD CONSTRAINT FK_D1FF6E818B777BD8 FOREIGN KEY (renown_id) REFERENCES renown (id)');
        $this->addSql('ALTER TABLE rite ADD CONSTRAINT FK_D1FF6E817B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE rite ADD CONSTRAINT FK_D1FF6E8116A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rite DROP FOREIGN KEY FK_D1FF6E818B777BD8');
        $this->addSql('ALTER TABLE rite DROP FOREIGN KEY FK_D1FF6E817B02D7EA');
        $this->addSql('ALTER TABLE rite DROP FOREIGN KEY FK_D1FF6E8116A2B381');
        $this->addSql('DROP TABLE rite');
        $this->addSql('DROP TABLE rite_translation');
    }
}
