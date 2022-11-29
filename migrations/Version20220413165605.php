<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220413165605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, category VARCHAR(20) NOT NULL, type VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, fluff LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_merit (id INT AUTO_INCREMENT NOT NULL, merit_id INT NOT NULL, character_id INT NOT NULL, choice VARCHAR(255) DEFAULT NULL, details JSON DEFAULT NULL, level SMALLINT NOT NULL, INDEX IDX_E1997D4758D79B5E (merit_id), INDEX IDX_E1997D471136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE characters (id INT AUTO_INCREMENT NOT NULL, virtue_id INT DEFAULT NULL, vice_id INT DEFAULT NULL, attributes_id INT DEFAULT NULL, skills_id INT DEFAULT NULL, user_id INT DEFAULT NULL, chronicle_id INT DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, age INT UNSIGNED DEFAULT NULL, player VARCHAR(25) DEFAULT NULL, concept VARCHAR(50) DEFAULT NULL, faction VARCHAR(25) DEFAULT NULL, group_name VARCHAR(25) DEFAULT NULL, willpower SMALLINT NOT NULL, moral SMALLINT NOT NULL, wounds JSON DEFAULT NULL, size SMALLINT NOT NULL, current_willpower SMALLINT NOT NULL, xp_total SMALLINT NOT NULL, xp_used SMALLINT NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_3A29410EE59AEF18 (virtue_id), INDEX IDX_3A29410E76457273 (vice_id), UNIQUE INDEX UNIQ_3A29410EBAAF4009 (attributes_id), UNIQUE INDEX UNIQ_3A29410E7FF61858 (skills_id), INDEX IDX_3A29410EA76ED395 (user_id), INDEX IDX_3A29410E237D532E (chronicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE characters_attributes (id INT AUTO_INCREMENT NOT NULL, intelligence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, wits SMALLINT UNSIGNED DEFAULT 1 NOT NULL, resolve SMALLINT UNSIGNED DEFAULT 1 NOT NULL, strength SMALLINT UNSIGNED DEFAULT 1 NOT NULL, dexterity SMALLINT UNSIGNED DEFAULT 1 NOT NULL, stamina SMALLINT UNSIGNED DEFAULT 1 NOT NULL, presence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, manipulation SMALLINT UNSIGNED DEFAULT 1 NOT NULL, composure SMALLINT UNSIGNED DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE characters_skills (id INT AUTO_INCREMENT NOT NULL, academics SMALLINT NOT NULL, computer SMALLINT NOT NULL, crafts SMALLINT NOT NULL, investigation SMALLINT NOT NULL, medicine SMALLINT NOT NULL, occult SMALLINT NOT NULL, politics SMALLINT NOT NULL, science SMALLINT NOT NULL, athletics SMALLINT NOT NULL, brawl SMALLINT NOT NULL, drive SMALLINT NOT NULL, firearms SMALLINT NOT NULL, larceny SMALLINT NOT NULL, stealth SMALLINT NOT NULL, survival SMALLINT NOT NULL, weaponry SMALLINT NOT NULL, animal_ken SMALLINT NOT NULL, empathy SMALLINT NOT NULL, expression SMALLINT NOT NULL, intimidation SMALLINT NOT NULL, persuasion SMALLINT NOT NULL, socialize SMALLINT NOT NULL, streetwise SMALLINT NOT NULL, subterfuge SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE characters_specialty (id INT AUTO_INCREMENT NOT NULL, character_id INT NOT NULL, skill_id INT NOT NULL, name VARCHAR(30) NOT NULL, INDEX IDX_90F0A3671136BE75 (character_id), INDEX IDX_90F0A3675585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chronicle (id INT AUTO_INCREMENT NOT NULL, storyteller_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DC91DBECC4AC16EC (storyteller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, short LONGTEXT NOT NULL, keywords VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clan_attribute (clan_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_50770B59BEAF84C8 (clan_id), INDEX IDX_50770B59B6E62EFA (attribute_id), PRIMARY KEY(clan_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clan_discipline (clan_id INT NOT NULL, discipline_id INT NOT NULL, INDEX IDX_4DCC211FBEAF84C8 (clan_id), INDEX IDX_4DCC211FA5522701 (discipline_id), PRIMARY KEY(clan_id, discipline_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discipline (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, is_restricted TINYINT(1) NOT NULL, rules LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discipline_power (id INT AUTO_INCREMENT NOT NULL, discipline_id INT NOT NULL, attribute_id INT DEFAULT NULL, skill_id INT DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, details LONGTEXT NOT NULL, level SMALLINT DEFAULT NULL, INDEX IDX_ECEDB2E8A5522701 (discipline_id), INDEX IDX_ECEDB2E8B6E62EFA (attribute_id), INDEX IDX_ECEDB2E85585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), INDEX general_translations_lookup_idx (object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE human (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merits (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, category VARCHAR(20) NOT NULL, is_fighting TINYINT(1) NOT NULL, is_expanded TINYINT(1) NOT NULL, min SMALLINT NOT NULL, max SMALLINT NOT NULL, is_unique TINYINT(1) NOT NULL, is_creation_only TINYINT(1) NOT NULL, prerequisites JSON DEFAULT NULL, effect LONGTEXT NOT NULL, description LONGTEXT NOT NULL, book VARCHAR(30) DEFAULT NULL, type VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE merits_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX merits_translation_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, category VARCHAR(20) NOT NULL, fluff LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, locale VARCHAR(10) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_chronicle (user_id INT NOT NULL, chronicle_id INT NOT NULL, INDEX IDX_DC715608A76ED395 (user_id), INDEX IDX_DC715608237D532E (chronicle_id), PRIMARY KEY(user_id, chronicle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vampire (id INT NOT NULL, clan_id INT NOT NULL, sire VARCHAR(50) NOT NULL, apparant_age SMALLINT NOT NULL, potency SMALLINT NOT NULL, vitae SMALLINT NOT NULL, INDEX IDX_BC368BFCBEAF84C8 (clan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vampire_discipline (id INT AUTO_INCREMENT NOT NULL, discipline_id INT NOT NULL, character_id INT NOT NULL, level SMALLINT NOT NULL, INDEX IDX_57BB63AAA5522701 (discipline_id), INDEX IDX_57BB63AA1136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(8) NOT NULL, details LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virtue (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, details LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_merit ADD CONSTRAINT FK_E1997D4758D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id)');
        $this->addSql('ALTER TABLE character_merit ADD CONSTRAINT FK_E1997D471136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EE59AEF18 FOREIGN KEY (virtue_id) REFERENCES virtue (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E76457273 FOREIGN KEY (vice_id) REFERENCES vice (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EBAAF4009 FOREIGN KEY (attributes_id) REFERENCES characters_attributes (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E7FF61858 FOREIGN KEY (skills_id) REFERENCES characters_skills (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E237D532E FOREIGN KEY (chronicle_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE characters_specialty ADD CONSTRAINT FK_90F0A3671136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters_specialty ADD CONSTRAINT FK_90F0A3675585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE chronicle ADD CONSTRAINT FK_DC91DBECC4AC16EC FOREIGN KEY (storyteller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE clan_attribute ADD CONSTRAINT FK_50770B59BEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clan_attribute ADD CONSTRAINT FK_50770B59B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clan_discipline ADD CONSTRAINT FK_4DCC211FBEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clan_discipline ADD CONSTRAINT FK_4DCC211FA5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E8A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E8B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E85585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE human ADD CONSTRAINT FK_A562D5F5BF396750 FOREIGN KEY (id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_chronicle ADD CONSTRAINT FK_DC715608A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_chronicle ADD CONSTRAINT FK_DC715608237D532E FOREIGN KEY (chronicle_id) REFERENCES chronicle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vampire ADD CONSTRAINT FK_BC368BFCBEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id)');
        $this->addSql('ALTER TABLE vampire ADD CONSTRAINT FK_BC368BFCBF396750 FOREIGN KEY (id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vampire_discipline ADD CONSTRAINT FK_57BB63AAA5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('ALTER TABLE vampire_discipline ADD CONSTRAINT FK_57BB63AA1136BE75 FOREIGN KEY (character_id) REFERENCES vampire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan_attribute DROP FOREIGN KEY FK_50770B59B6E62EFA');
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E8B6E62EFA');
        $this->addSql('ALTER TABLE character_merit DROP FOREIGN KEY FK_E1997D471136BE75');
        $this->addSql('ALTER TABLE characters_specialty DROP FOREIGN KEY FK_90F0A3671136BE75');
        $this->addSql('ALTER TABLE human DROP FOREIGN KEY FK_A562D5F5BF396750');
        $this->addSql('ALTER TABLE vampire DROP FOREIGN KEY FK_BC368BFCBF396750');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EBAAF4009');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E7FF61858');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E237D532E');
        $this->addSql('ALTER TABLE user_chronicle DROP FOREIGN KEY FK_DC715608237D532E');
        $this->addSql('ALTER TABLE clan_attribute DROP FOREIGN KEY FK_50770B59BEAF84C8');
        $this->addSql('ALTER TABLE clan_discipline DROP FOREIGN KEY FK_4DCC211FBEAF84C8');
        $this->addSql('ALTER TABLE vampire DROP FOREIGN KEY FK_BC368BFCBEAF84C8');
        $this->addSql('ALTER TABLE clan_discipline DROP FOREIGN KEY FK_4DCC211FA5522701');
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E8A5522701');
        $this->addSql('ALTER TABLE vampire_discipline DROP FOREIGN KEY FK_57BB63AAA5522701');
        $this->addSql('ALTER TABLE character_merit DROP FOREIGN KEY FK_E1997D4758D79B5E');
        $this->addSql('ALTER TABLE characters_specialty DROP FOREIGN KEY FK_90F0A3675585C142');
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E85585C142');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EA76ED395');
        $this->addSql('ALTER TABLE chronicle DROP FOREIGN KEY FK_DC91DBECC4AC16EC');
        $this->addSql('ALTER TABLE user_chronicle DROP FOREIGN KEY FK_DC715608A76ED395');
        $this->addSql('ALTER TABLE vampire_discipline DROP FOREIGN KEY FK_57BB63AA1136BE75');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E76457273');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EE59AEF18');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE character_merit');
        $this->addSql('DROP TABLE characters');
        $this->addSql('DROP TABLE characters_attributes');
        $this->addSql('DROP TABLE characters_skills');
        $this->addSql('DROP TABLE characters_specialty');
        $this->addSql('DROP TABLE chronicle');
        $this->addSql('DROP TABLE clan');
        $this->addSql('DROP TABLE clan_attribute');
        $this->addSql('DROP TABLE clan_discipline');
        $this->addSql('DROP TABLE discipline');
        $this->addSql('DROP TABLE discipline_power');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE human');
        $this->addSql('DROP TABLE merits');
        $this->addSql('DROP TABLE merits_translations');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_chronicle');
        $this->addSql('DROP TABLE vampire');
        $this->addSql('DROP TABLE vampire_discipline');
        $this->addSql('DROP TABLE vice');
        $this->addSql('DROP TABLE virtue');
    }
}
