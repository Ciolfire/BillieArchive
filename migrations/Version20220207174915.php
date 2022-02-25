<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220207174915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE merit (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, category VARCHAR(20) NOT NULL, is_fighting TINYINT(1) NOT NULL, is_expanded TINYINT(1) NOT NULL, min INT NOT NULL, max SMALLINT NOT NULL, is_unique TINYINT(1) NOT NULL, is_creation_only TINYINT(1) NOT NULL, prerequisites JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE merit');
    }
}
