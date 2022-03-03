<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303002358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_skills (id INT AUTO_INCREMENT NOT NULL, academics SMALLINT NOT NULL, computer SMALLINT NOT NULL, crafts SMALLINT NOT NULL, investigation SMALLINT NOT NULL, medecine SMALLINT NOT NULL, occult SMALLINT NOT NULL, politics SMALLINT NOT NULL, science SMALLINT NOT NULL, athletics SMALLINT NOT NULL, brawl SMALLINT NOT NULL, drive SMALLINT NOT NULL, firearms SMALLINT NOT NULL, larceny SMALLINT NOT NULL, stealth SMALLINT NOT NULL, survival SMALLINT NOT NULL, weaponry SMALLINT NOT NULL, animal_ken SMALLINT NOT NULL, empathy SMALLINT NOT NULL, expression SMALLINT NOT NULL, intimidation SMALLINT NOT NULL, persuasion SMALLINT NOT NULL, socialize SMALLINT NOT NULL, streetwise SMALLINT NOT NULL, subterfuge SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters ADD skills_id INT DEFAULT NULL, DROP skills');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E7FF61858 FOREIGN KEY (skills_id) REFERENCES character_skills (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3A29410E7FF61858 ON characters (skills_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E7FF61858');
        $this->addSql('DROP TABLE character_skills');
        $this->addSql('DROP INDEX UNIQ_3A29410E7FF61858 ON characters');
        $this->addSql('ALTER TABLE characters ADD skills JSON DEFAULT NULL, DROP skills_id');
    }
}
