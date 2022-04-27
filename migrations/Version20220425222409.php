<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425222409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan ADD parent_clan_id INT DEFAULT NULL, DROP is_bloodline');
        $this->addSql('ALTER TABLE clan ADD CONSTRAINT FK_9FF6A30CCCE7F08E FOREIGN KEY (parent_clan_id) REFERENCES clan (id)');
        $this->addSql('CREATE INDEX IDX_9FF6A30CCCE7F08E ON clan (parent_clan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan DROP FOREIGN KEY FK_9FF6A30CCCE7F08E');
        $this->addSql('DROP INDEX IDX_9FF6A30CCCE7F08E ON clan');
        $this->addSql('ALTER TABLE clan ADD is_bloodline TINYINT(1) NOT NULL, DROP parent_clan_id');
    }
}
