<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626220010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_container TINYINT(1) NOT NULL, is_weapon TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, type SMALLINT NOT NULL, durability SMALLINT NOT NULL, size SMALLINT NOT NULL, structure SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters CHANGE avatar avatar VARCHAR(255) DEFAULT NULL');
    }
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item');
        $this->addSql('ALTER TABLE characters CHANGE avatar avatar VARCHAR(50) DEFAULT NULL');
    }
}
