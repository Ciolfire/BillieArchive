<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206101313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE thaumaturge_tradition (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, strengths LONGTEXT NOT NULL, weaknesses LONGTEXT NOT NULL, quote VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, creation LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, defining_merit_id INT NOT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_21FBB4CD283CD845 (defining_merit_id), INDEX IDX_21FBB4CD16A2B381 (book_id), INDEX IDX_21FBB4CD7B02D7EA (homebrew_for_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE thaumaturge_tradition_merit (thaumaturge_tradition_id INT NOT NULL, merit_id INT NOT NULL, INDEX IDX_B5BB687655F0802A (thaumaturge_tradition_id), INDEX IDX_B5BB687658D79B5E (merit_id), PRIMARY KEY(thaumaturge_tradition_id, merit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE thaumaturge_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX thaumaturge_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE thaumaturge_tradition ADD CONSTRAINT FK_21FBB4CD283CD845 FOREIGN KEY (defining_merit_id) REFERENCES merits (id)');
        $this->addSql('ALTER TABLE thaumaturge_tradition ADD CONSTRAINT FK_21FBB4CD16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE thaumaturge_tradition ADD CONSTRAINT FK_21FBB4CD7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE thaumaturge_tradition_merit ADD CONSTRAINT FK_B5BB687655F0802A FOREIGN KEY (thaumaturge_tradition_id) REFERENCES thaumaturge_tradition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thaumaturge_tradition_merit ADD CONSTRAINT FK_B5BB687658D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thaumaturge ADD tradition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE thaumaturge ADD CONSTRAINT FK_718FEDF8649E4584 FOREIGN KEY (tradition_id) REFERENCES thaumaturge_tradition (id)');
        $this->addSql('CREATE INDEX IDX_718FEDF8649E4584 ON thaumaturge (tradition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thaumaturge_tradition DROP FOREIGN KEY FK_21FBB4CD283CD845');
        $this->addSql('ALTER TABLE thaumaturge_tradition DROP FOREIGN KEY FK_21FBB4CD16A2B381');
        $this->addSql('ALTER TABLE thaumaturge_tradition DROP FOREIGN KEY FK_21FBB4CD7B02D7EA');
        $this->addSql('ALTER TABLE thaumaturge_tradition_merit DROP FOREIGN KEY FK_B5BB687655F0802A');
        $this->addSql('ALTER TABLE thaumaturge_tradition_merit DROP FOREIGN KEY FK_B5BB687658D79B5E');
        $this->addSql('DROP TABLE thaumaturge_tradition');
        $this->addSql('DROP TABLE thaumaturge_tradition_merit');
        $this->addSql('DROP TABLE thaumaturge_translation');
        $this->addSql('ALTER TABLE thaumaturge DROP FOREIGN KEY FK_718FEDF8649E4584');
        $this->addSql('DROP INDEX IDX_718FEDF8649E4584 ON thaumaturge');
        $this->addSql('ALTER TABLE thaumaturge DROP tradition_id');
    }
}
