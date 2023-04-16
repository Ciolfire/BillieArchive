<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223132914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE derangement (id INT AUTO_INCREMENT NOT NULL, previous_ailment_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, type VARCHAR(255) DEFAULT NULL, is_extreme TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_CABCF56A342C9683 (previous_ailment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE derangements_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX derangements_translation_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE derangement ADD CONSTRAINT FK_CABCF56A342C9683 FOREIGN KEY (previous_ailment_id) REFERENCES derangement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE derangement DROP FOREIGN KEY FK_CABCF56A342C9683');
        $this->addSql('DROP TABLE derangement');
        $this->addSql('DROP TABLE derangements_translations');
    }
}
