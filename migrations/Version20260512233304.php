<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512233304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_flaw (character_id INT NOT NULL, flaw_id INT NOT NULL, INDEX IDX_DBBFEF661136BE75 (character_id), INDEX IDX_DBBFEF66C186B37A (flaw_id), PRIMARY KEY (character_id, flaw_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE character_flaw ADD CONSTRAINT FK_DBBFEF661136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_flaw ADD CONSTRAINT FK_DBBFEF66C186B37A FOREIGN KEY (flaw_id) REFERENCES flaw (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_flaw DROP FOREIGN KEY FK_DBBFEF661136BE75');
        $this->addSql('ALTER TABLE character_flaw DROP FOREIGN KEY FK_DBBFEF66C186B37A');
        $this->addSql('DROP TABLE character_flaw');
    }
}
