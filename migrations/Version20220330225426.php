<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330225426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX name ON discipline');
        $this->addSql('DROP INDEX name_2 ON discipline');
        $this->addSql('ALTER TABLE discipline_power ADD level SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX name ON discipline (name)');
        $this->addSql('CREATE INDEX name_2 ON discipline (name)');
        $this->addSql('ALTER TABLE discipline_power DROP level');
    }
}
