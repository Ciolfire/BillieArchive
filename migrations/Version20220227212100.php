<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220227212100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits ADD book VARCHAR(30) DEFAULT NULL, CHANGE is_fighting is_fighting TINYINT(1) NOT NULL, CHANGE is_expanded is_expanded TINYINT(1) NOT NULL, CHANGE is_unique is_unique TINYINT(1) NOT NULL, CHANGE is_creation_only is_creation_only TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits DROP book, CHANGE is_fighting is_fighting TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_expanded is_expanded TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE is_unique is_unique TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE is_creation_only is_creation_only TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
