<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004205722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_derangement ADD derangement_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_derangement ADD CONSTRAINT FK_CED65FC6881D6153 FOREIGN KEY (derangement_id) REFERENCES derangement (id)');
        $this->addSql('CREATE INDEX IDX_CED65FC6881D6153 ON character_derangement (derangement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_derangement DROP FOREIGN KEY FK_CED65FC6881D6153');
        $this->addSql('DROP INDEX IDX_CED65FC6881D6153 ON character_derangement');
        $this->addSql('ALTER TABLE character_derangement DROP derangement_id');
    }
}
