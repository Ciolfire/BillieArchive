<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220130220628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) DEFAULT NULL, age INT DEFAULT NULL, player VARCHAR(25) DEFAULT NULL, virtue VARCHAR(25) DEFAULT NULL, vice VARCHAR(25) DEFAULT NULL, concept VARCHAR(50) DEFAULT NULL, chronicle VARCHAR(25) DEFAULT NULL, faction VARCHAR(25) DEFAULT NULL, group_name VARCHAR(25) DEFAULT NULL, intelligence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, wits SMALLINT UNSIGNED DEFAULT 1 NOT NULL, resolve SMALLINT UNSIGNED DEFAULT 1 NOT NULL, strength SMALLINT UNSIGNED DEFAULT 1 NOT NULL, dexterity SMALLINT UNSIGNED DEFAULT 1 NOT NULL, stamina SMALLINT UNSIGNED DEFAULT 1 NOT NULL, presence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, manipulation SMALLINT UNSIGNED DEFAULT 1 NOT NULL, composure SMALLINT UNSIGNED DEFAULT 1 NOT NULL, merits JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `character`');
    }
}
