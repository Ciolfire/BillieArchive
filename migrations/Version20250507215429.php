<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507215429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spell_rote ADD spell_id INT NOT NULL');
        $this->addSql('ALTER TABLE spell_rote ADD CONSTRAINT FK_EB094762479EC90D FOREIGN KEY (spell_id) REFERENCES mage_spell (id)');
        $this->addSql('CREATE INDEX IDX_EB094762479EC90D ON spell_rote (spell_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spell_rote DROP FOREIGN KEY FK_EB094762479EC90D');
        $this->addSql('DROP INDEX IDX_EB094762479EC90D ON spell_rote');
        $this->addSql('ALTER TABLE spell_rote DROP spell_id');
    }
}
