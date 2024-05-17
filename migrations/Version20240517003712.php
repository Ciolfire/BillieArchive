<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517003712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX book_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discipline_power_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX discipline_power_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ghoul_family_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX ghoul_family_translation_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX rule_translation_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('RENAME TABLE `attributes_translations` TO `attribute_translation`');
        $this->addSql('RENAME TABLE `clan_translations` TO `clan_translation`');
        $this->addSql('RENAME TABLE `derangements_translations` TO `derangement_translation`');
        $this->addSql('RENAME TABLE `devotions_translations` TO `devotion_translation`');
        $this->addSql('RENAME TABLE `disciplines_translations` TO `discipline_translation`');
        $this->addSql('RENAME TABLE `merits_translations` TO `merit_translation`');
        $this->addSql('RENAME TABLE `rolls_translations` TO `roll_translation`');
        $this->addSql('RENAME TABLE `skills_translations` TO `skill_translation`');
        $this->addSql('RENAME TABLE `vice_translations` TO `vice_translation`');
        $this->addSql('RENAME TABLE `virtue_translations` TO `virtue_translation`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE book_translation');
        $this->addSql('DROP TABLE discipline_power_translation');
        $this->addSql('DROP TABLE ghoul_family_translation');
        $this->addSql('DROP TABLE rule_translation');
        $this->addSql('RENAME TABLE `attribute_translation` TO `attributes_translations`');
        $this->addSql('RENAME TABLE `clan_translation` TO `clan_translations`');
        $this->addSql('RENAME TABLE `derangement_translation` TO `derangements_translations`');
        $this->addSql('RENAME TABLE `devotion_translation` TO `devotions_translations`');
        $this->addSql('RENAME TABLE `discipline_translation` TO `disciplines_translations`');
        $this->addSql('RENAME TABLE `merit_translation` TO `merits_translations`');
        $this->addSql('RENAME TABLE `roll_translation` TO `rolls_translations`');
        $this->addSql('RENAME TABLE `skill_translation` TO `skills_translations`');
        $this->addSql('RENAME TABLE `vice_translation` TO `vice_translations`');
        $this->addSql('RENAME TABLE `virtue_translation` TO `virtue_translations`');
    }
}
