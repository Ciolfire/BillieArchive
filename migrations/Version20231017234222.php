<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017234222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_lesser_template (id INT AUTO_INCREMENT NOT NULL, source_character_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4B375547C7DC62CD (source_character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ghoul (id INT NOT NULL, regent_clan_id INT NOT NULL, vitae SMALLINT NOT NULL, INDEX IDX_4665AF71798BC459 (regent_clan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ghoul_devotion (ghoul_id INT NOT NULL, devotion_id INT NOT NULL, INDEX IDX_7FDA0D9C2735F2E0 (ghoul_id), INDEX IDX_7FDA0D9C5871450E (devotion_id), PRIMARY KEY(ghoul_id, devotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ghoul_discipline_power (ghoul_id INT NOT NULL, discipline_power_id INT NOT NULL, INDEX IDX_ABD474F02735F2E0 (ghoul_id), INDEX IDX_ABD474F0C9F8163B (discipline_power_id), PRIMARY KEY(ghoul_id, discipline_power_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ghoul_discipline (id INT AUTO_INCREMENT NOT NULL, discipline_id INT NOT NULL, character_id INT NOT NULL, level SMALLINT NOT NULL, INDEX IDX_880CCDA9A5522701 (discipline_id), INDEX IDX_880CCDA91136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_lesser_template ADD CONSTRAINT FK_4B375547C7DC62CD FOREIGN KEY (source_character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE ghoul ADD CONSTRAINT FK_4665AF71798BC459 FOREIGN KEY (regent_clan_id) REFERENCES clan (id)');
        $this->addSql('ALTER TABLE ghoul ADD CONSTRAINT FK_4665AF71BF396750 FOREIGN KEY (id) REFERENCES character_lesser_template (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ghoul_devotion ADD CONSTRAINT FK_7FDA0D9C2735F2E0 FOREIGN KEY (ghoul_id) REFERENCES ghoul (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ghoul_devotion ADD CONSTRAINT FK_7FDA0D9C5871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ghoul_discipline_power ADD CONSTRAINT FK_ABD474F02735F2E0 FOREIGN KEY (ghoul_id) REFERENCES ghoul (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ghoul_discipline_power ADD CONSTRAINT FK_ABD474F0C9F8163B FOREIGN KEY (discipline_power_id) REFERENCES discipline_power (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ghoul_discipline ADD CONSTRAINT FK_880CCDA9A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('ALTER TABLE ghoul_discipline ADD CONSTRAINT FK_880CCDA91136BE75 FOREIGN KEY (character_id) REFERENCES ghoul (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_lesser_template DROP FOREIGN KEY FK_4B375547C7DC62CD');
        $this->addSql('ALTER TABLE ghoul DROP FOREIGN KEY FK_4665AF71798BC459');
        $this->addSql('ALTER TABLE ghoul DROP FOREIGN KEY FK_4665AF71BF396750');
        $this->addSql('ALTER TABLE ghoul_devotion DROP FOREIGN KEY FK_7FDA0D9C2735F2E0');
        $this->addSql('ALTER TABLE ghoul_devotion DROP FOREIGN KEY FK_7FDA0D9C5871450E');
        $this->addSql('ALTER TABLE ghoul_discipline_power DROP FOREIGN KEY FK_ABD474F02735F2E0');
        $this->addSql('ALTER TABLE ghoul_discipline_power DROP FOREIGN KEY FK_ABD474F0C9F8163B');
        $this->addSql('ALTER TABLE ghoul_discipline DROP FOREIGN KEY FK_880CCDA9A5522701');
        $this->addSql('ALTER TABLE ghoul_discipline DROP FOREIGN KEY FK_880CCDA91136BE75');
        $this->addSql('DROP TABLE character_lesser_template');
        $this->addSql('DROP TABLE ghoul');
        $this->addSql('DROP TABLE ghoul_devotion');
        $this->addSql('DROP TABLE ghoul_discipline_power');
        $this->addSql('DROP TABLE ghoul_discipline');
    }
}
