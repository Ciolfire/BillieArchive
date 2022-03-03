<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302234656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD skills JSON DEFAULT NULL, ADD moral SMALLINT NOT NULL');
        $this->addSql('DROP INDEX name ON skill');
        $this->addSql('DROP INDEX name_2 ON skill');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP skills, DROP moral');
        $this->addSql('CREATE UNIQUE INDEX name ON skill (name)');
        $this->addSql('CREATE INDEX name_2 ON skill (name)');
    }
}
