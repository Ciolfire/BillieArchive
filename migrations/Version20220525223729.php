<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525223729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX merits_translation_idx ON merits_translations');
        $this->addSql('CREATE INDEX merits_translation_idx ON merits_translations (locale, field, foreign_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX merits_translation_idx ON merits_translations');
        $this->addSql('CREATE INDEX merits_translation_idx ON merits_translations (locale, object_class, field, foreign_key)');
    }
}
