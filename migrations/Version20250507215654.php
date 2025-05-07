<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507215654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spell_rote ADD attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE spell_rote ADD CONSTRAINT FK_EB094762B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('CREATE INDEX IDX_EB094762B6E62EFA ON spell_rote (attribute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spell_rote DROP FOREIGN KEY FK_EB094762B6E62EFA');
        $this->addSql('DROP INDEX IDX_EB094762B6E62EFA ON spell_rote');
        $this->addSql('ALTER TABLE spell_rote DROP attribute_id');
    }
}
