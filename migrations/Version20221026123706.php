<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026123706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX skills_translation_idx ON skills_translations');
        $this->addSql('CREATE INDEX skills_translation_idx ON skills_translations (locale, object_class, field, foreign_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX skills_translation_idx ON skills_translations');
        $this->addSql('CREATE INDEX skills_translation_idx ON skills_translations (locale, field, foreign_key)');
    }
}
