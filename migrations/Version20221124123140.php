<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124123140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters_specialty CHANGE character_id character_id INT DEFAULT NULL, CHANGE skill_id skill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline CHANGE short short LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE merits DROP book');
        $this->addSql('ALTER TABLE vice CHANGE details details LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE virtue CHANGE details details LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters_specialty CHANGE character_id character_id INT NOT NULL, CHANGE skill_id skill_id INT NOT NULL');
        $this->addSql('ALTER TABLE discipline CHANGE short short LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE merits ADD book VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE vice CHANGE details details LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE virtue CHANGE details details LONGTEXT NOT NULL');
    }
}
