<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507190252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE spell_rote (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, page SMALLINT DEFAULT NULL, mage_order_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_EB094762FE2BACBC (mage_order_id), INDEX IDX_EB0947627B02D7EA (homebrew_for_id), INDEX IDX_EB09476216A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE spell_rote_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX spell_rote_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE spell_rote ADD CONSTRAINT FK_EB094762FE2BACBC FOREIGN KEY (mage_order_id) REFERENCES organization_order (id)');
        $this->addSql('ALTER TABLE spell_rote ADD CONSTRAINT FK_EB0947627B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE spell_rote ADD CONSTRAINT FK_EB09476216A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spell_rote DROP FOREIGN KEY FK_EB094762FE2BACBC');
        $this->addSql('ALTER TABLE spell_rote DROP FOREIGN KEY FK_EB0947627B02D7EA');
        $this->addSql('ALTER TABLE spell_rote DROP FOREIGN KEY FK_EB09476216A2B381');
        $this->addSql('DROP TABLE spell_rote');
        $this->addSql('DROP TABLE spell_rote_translation');
    }
}
