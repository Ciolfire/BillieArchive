<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203030451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devotion (id INT AUTO_INCREMENT NOT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, cost SMALLINT NOT NULL, description LONGTEXT NOT NULL, short VARCHAR(255) NOT NULL, page SMALLINT DEFAULT NULL, INDEX IDX_C520E0797B02D7EA (homebrew_for_id), INDEX IDX_C520E07916A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devotion_prerequisite (devotion_id INT NOT NULL, prerequisite_id INT NOT NULL, INDEX IDX_7231FF275871450E (devotion_id), INDEX IDX_7231FF27276AF86B (prerequisite_id), PRIMARY KEY(devotion_id, prerequisite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devotions_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX devotions_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devotion ADD CONSTRAINT FK_C520E0797B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE devotion ADD CONSTRAINT FK_C520E07916A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE devotion_prerequisite ADD CONSTRAINT FK_7231FF275871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devotion_prerequisite ADD CONSTRAINT FK_7231FF27276AF86B FOREIGN KEY (prerequisite_id) REFERENCES prerequisite (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devotion DROP FOREIGN KEY FK_C520E0797B02D7EA');
        $this->addSql('ALTER TABLE devotion DROP FOREIGN KEY FK_C520E07916A2B381');
        $this->addSql('ALTER TABLE devotion_prerequisite DROP FOREIGN KEY FK_7231FF275871450E');
        $this->addSql('ALTER TABLE devotion_prerequisite DROP FOREIGN KEY FK_7231FF27276AF86B');
        $this->addSql('DROP TABLE devotion');
        $this->addSql('DROP TABLE devotion_prerequisite');
        $this->addSql('DROP TABLE devotions_translations');
    }
}
