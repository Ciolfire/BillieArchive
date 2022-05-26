<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526183316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX attributes_translation_idx ON attributes_translations');
        $this->addSql('CREATE INDEX attributes_translation_idx ON attributes_translations (locale, field, foreign_key)');
        $this->addSql('ALTER TABLE skill ADD identifier VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX attributes_translation_idx ON attributes_translations');
        $this->addSql('CREATE INDEX attributes_translation_idx ON attributes_translations (locale, object_class, field, foreign_key)');
        $this->addSql('ALTER TABLE skill DROP identifier');
    }
}
