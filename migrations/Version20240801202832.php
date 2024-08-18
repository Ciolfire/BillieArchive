<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240801202832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ranged_weapon (id INT NOT NULL, damage SMALLINT NOT NULL, ranges VARCHAR(255) NOT NULL, clip VARCHAR(255) NOT NULL, strength SMALLINT NOT NULL, special JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ranged_weapon ADD CONSTRAINT FK_188BF8ACBF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP structure');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranged_weapon DROP FOREIGN KEY FK_188BF8ACBF396750');
        $this->addSql('DROP TABLE ranged_weapon');
        $this->addSql('ALTER TABLE item ADD structure VARCHAR(10) DEFAULT NULL');
    }
}
