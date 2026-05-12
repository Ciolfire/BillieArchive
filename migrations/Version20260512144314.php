<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512144314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flaw (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, category VARCHAR(20) NOT NULL, effect LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, type_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_CC43FBFEC54C8C93 (type_id), INDEX IDX_CC43FBFE7B02D7EA (homebrew_for_id), INDEX IDX_CC43FBFE16A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE flaw_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX flaw_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE flaw ADD CONSTRAINT FK_CC43FBFEC54C8C93 FOREIGN KEY (type_id) REFERENCES content_type (id)');
        $this->addSql('ALTER TABLE flaw ADD CONSTRAINT FK_CC43FBFE7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE flaw ADD CONSTRAINT FK_CC43FBFE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flaw DROP FOREIGN KEY FK_CC43FBFEC54C8C93');
        $this->addSql('ALTER TABLE flaw DROP FOREIGN KEY FK_CC43FBFE7B02D7EA');
        $this->addSql('ALTER TABLE flaw DROP FOREIGN KEY FK_CC43FBFE16A2B381');
        $this->addSql('DROP TABLE flaw');
        $this->addSql('DROP TABLE flaw_translation');
    }
}
