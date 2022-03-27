<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220326230917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vampire (id INT NOT NULL, clan_id INT NOT NULL, sire VARCHAR(50) NOT NULL, apparant_age SMALLINT NOT NULL, INDEX IDX_BC368BFCBEAF84C8 (clan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vampire ADD CONSTRAINT FK_BC368BFCBEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id)');
        $this->addSql('ALTER TABLE vampire ADD CONSTRAINT FK_BC368BFCBF396750 FOREIGN KEY (id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE characters ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE discipline CHANGE is_restricted is_restricted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vampire');
        $this->addSql('ALTER TABLE characters DROP type');
        $this->addSql('ALTER TABLE discipline CHANGE is_restricted is_restricted TINYINT(1) DEFAULT \'1\' NOT NULL');
    }
}
