<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260222193927 extends AbstractMigration
{
  public function getDescription(): string
  {
    return '';
  }

  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->addSql('CREATE TABLE auspice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, extended_name VARCHAR(40) NOT NULL, short VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, ability LONGTEXT NOT NULL, the_change LONGTEXT NOT NULL, quote LONGTEXT DEFAULT NULL, page SMALLINT DEFAULT NULL, renown_id INT NOT NULL, phase_gift_id INT NOT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_A0163C28B777BD8 (renown_id), INDEX IDX_A0163C287CDF7EF (phase_gift_id), INDEX IDX_A0163C216A2B381 (book_id), INDEX IDX_A0163C27B02D7EA (homebrew_for_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE auspice_skill (auspice_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_E39C587ACDBD8FE6 (auspice_id), INDEX IDX_E39C587A5585C142 (skill_id), PRIMARY KEY (auspice_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE auspice_gift_list (auspice_id INT NOT NULL, gift_list_id INT NOT NULL, INDEX IDX_8A579775CDBD8FE6 (auspice_id), INDEX IDX_8A57977551F42524 (gift_list_id), PRIMARY KEY (auspice_id, gift_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE auspice_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX auspice_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE gift (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, level SMALLINT NOT NULL, page SMALLINT DEFAULT NULL, list_id INT NOT NULL, attribute_id INT DEFAULT NULL, skill_id INT DEFAULT NULL, renown_id INT DEFAULT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_A47C990D3DAE168B (list_id), INDEX IDX_A47C990DB6E62EFA (attribute_id), INDEX IDX_A47C990D5585C142 (skill_id), INDEX IDX_A47C990D8B777BD8 (renown_id), INDEX IDX_A47C990D16A2B381 (book_id), INDEX IDX_A47C990D7B02D7EA (homebrew_for_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE gift_list (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, short VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, rules LONGTEXT NOT NULL, is_common TINYINT(1) NOT NULL, page SMALLINT DEFAULT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_B6B50A4516A2B381 (book_id), INDEX IDX_B6B50A457B02D7EA (homebrew_for_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE gift_list_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX gift_list_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE gift_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX gift_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE renown (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, description LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_F4FC5A1716A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE renown_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX renown_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE tribe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, short VARCHAR(255) NOT NULL, emblem VARCHAR(255) DEFAULT NULL, nickname VARCHAR(50) NOT NULL, quote VARCHAR(255) DEFAULT NULL, page SMALLINT DEFAULT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_2653B55816A2B381 (book_id), INDEX IDX_2653B5587B02D7EA (homebrew_for_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE tribe_gift_list (tribe_id INT NOT NULL, gift_list_id INT NOT NULL, INDEX IDX_24DD10B56F3EE0AD (tribe_id), INDEX IDX_24DD10B551F42524 (gift_list_id), PRIMARY KEY (tribe_id, gift_list_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE tribe_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX tribe_translation_idx (locale, field, foreign_key), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE werewolf_gift (werewolf_id INT NOT NULL, gift_id INT NOT NULL, INDEX IDX_D94BCB96C6EC01BF (werewolf_id), INDEX IDX_D94BCB9697A95A83 (gift_id), PRIMARY KEY (werewolf_id, gift_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('CREATE TABLE werewolf_renown (id INT AUTO_INCREMENT NOT NULL, cunning SMALLINT NOT NULL, glory SMALLINT NOT NULL, honor SMALLINT NOT NULL, purity SMALLINT NOT NULL, wisdom SMALLINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    $this->addSql('ALTER TABLE auspice ADD CONSTRAINT FK_A0163C28B777BD8 FOREIGN KEY (renown_id) REFERENCES renown (id)');
    $this->addSql('ALTER TABLE auspice ADD CONSTRAINT FK_A0163C287CDF7EF FOREIGN KEY (phase_gift_id) REFERENCES gift_list (id)');
    $this->addSql('ALTER TABLE auspice ADD CONSTRAINT FK_A0163C216A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    $this->addSql('ALTER TABLE auspice ADD CONSTRAINT FK_A0163C27B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
    $this->addSql('ALTER TABLE auspice_skill ADD CONSTRAINT FK_E39C587ACDBD8FE6 FOREIGN KEY (auspice_id) REFERENCES auspice (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE auspice_skill ADD CONSTRAINT FK_E39C587A5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE auspice_gift_list ADD CONSTRAINT FK_8A579775CDBD8FE6 FOREIGN KEY (auspice_id) REFERENCES auspice (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE auspice_gift_list ADD CONSTRAINT FK_8A57977551F42524 FOREIGN KEY (gift_list_id) REFERENCES gift_list (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D3DAE168B FOREIGN KEY (list_id) REFERENCES gift_list (id)');
    $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
    $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
    $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D8B777BD8 FOREIGN KEY (renown_id) REFERENCES renown (id)');
    $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
    $this->addSql('ALTER TABLE gift_list ADD CONSTRAINT FK_B6B50A4516A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    $this->addSql('ALTER TABLE gift_list ADD CONSTRAINT FK_B6B50A457B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
    $this->addSql('ALTER TABLE renown ADD CONSTRAINT FK_F4FC5A1716A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    $this->addSql('ALTER TABLE tribe ADD CONSTRAINT FK_2653B55816A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    $this->addSql('ALTER TABLE tribe ADD CONSTRAINT FK_2653B5587B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
    $this->addSql('ALTER TABLE tribe_gift_list ADD CONSTRAINT FK_24DD10B56F3EE0AD FOREIGN KEY (tribe_id) REFERENCES tribe (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE tribe_gift_list ADD CONSTRAINT FK_24DD10B551F42524 FOREIGN KEY (gift_list_id) REFERENCES gift_list (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE werewolf_gift ADD CONSTRAINT FK_D94BCB96C6EC01BF FOREIGN KEY (werewolf_id) REFERENCES werewolf (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE werewolf_gift ADD CONSTRAINT FK_D94BCB9697A95A83 FOREIGN KEY (gift_id) REFERENCES gift (id) ON DELETE CASCADE');
    $this->addSql('ALTER TABLE werewolf ADD primal_urge SMALLINT NOT NULL, ADD essence SMALLINT NOT NULL, ADD auspice_id INT NOT NULL, ADD tribe_id INT DEFAULT NULL, ADD renowns_id INT DEFAULT NULL');
    $this->addSql('ALTER TABLE werewolf ADD CONSTRAINT FK_D5FB84ABCDBD8FE6 FOREIGN KEY (auspice_id) REFERENCES auspice (id)');
    $this->addSql('ALTER TABLE werewolf ADD CONSTRAINT FK_D5FB84AB6F3EE0AD FOREIGN KEY (tribe_id) REFERENCES tribe (id)');
    $this->addSql('ALTER TABLE werewolf ADD CONSTRAINT FK_D5FB84AB6FF290B8 FOREIGN KEY (renowns_id) REFERENCES werewolf_renown (id)');
    $this->addSql('CREATE INDEX IDX_D5FB84ABCDBD8FE6 ON werewolf (auspice_id)');
    $this->addSql('CREATE INDEX IDX_D5FB84AB6F3EE0AD ON werewolf (tribe_id)');
    $this->addSql('CREATE UNIQUE INDEX UNIQ_D5FB84AB6FF290B8 ON werewolf (renowns_id)');
    $this->addSql('ALTER TABLE auspice ADD emblem VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE auspice ADD ability_name VARCHAR(50) NOT NULL, CHANGE quote quote LONGTEXT DEFAULT NULL');
    $this->addSql('ALTER TABLE tribe ADD renown_id INT DEFAULT NULL');
    $this->addSql('ALTER TABLE tribe ADD CONSTRAINT FK_2653B5588B777BD8 FOREIGN KEY (renown_id) REFERENCES renown (id)');
    $this->addSql('CREATE INDEX IDX_2653B5588B777BD8 ON tribe (renown_id)');
    $this->addSql('ALTER TABLE gift ADD cost VARCHAR(255) NOT NULL, ADD action SMALLINT NOT NULL, ADD is_contested TINYINT(1) NOT NULL, ADD contested_text VARCHAR(255) DEFAULT NULL');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->addSql('ALTER TABLE auspice DROP FOREIGN KEY FK_A0163C28B777BD8');
    $this->addSql('ALTER TABLE auspice DROP FOREIGN KEY FK_A0163C287CDF7EF');
    $this->addSql('ALTER TABLE auspice DROP FOREIGN KEY FK_A0163C216A2B381');
    $this->addSql('ALTER TABLE auspice DROP FOREIGN KEY FK_A0163C27B02D7EA');
    $this->addSql('ALTER TABLE auspice_skill DROP FOREIGN KEY FK_E39C587ACDBD8FE6');
    $this->addSql('ALTER TABLE auspice_skill DROP FOREIGN KEY FK_E39C587A5585C142');
    $this->addSql('ALTER TABLE auspice_gift_list DROP FOREIGN KEY FK_8A579775CDBD8FE6');
    $this->addSql('ALTER TABLE auspice_gift_list DROP FOREIGN KEY FK_8A57977551F42524');
    $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D3DAE168B');
    $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990DB6E62EFA');
    $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D5585C142');
    $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D8B777BD8');
    $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D16A2B381');
    $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D7B02D7EA');
    $this->addSql('ALTER TABLE gift_list DROP FOREIGN KEY FK_B6B50A4516A2B381');
    $this->addSql('ALTER TABLE gift_list DROP FOREIGN KEY FK_B6B50A457B02D7EA');
    $this->addSql('ALTER TABLE renown DROP FOREIGN KEY FK_F4FC5A1716A2B381');
    $this->addSql('ALTER TABLE tribe DROP FOREIGN KEY FK_2653B55816A2B381');
    $this->addSql('ALTER TABLE tribe DROP FOREIGN KEY FK_2653B5587B02D7EA');
    $this->addSql('ALTER TABLE tribe_gift_list DROP FOREIGN KEY FK_24DD10B56F3EE0AD');
    $this->addSql('ALTER TABLE tribe_gift_list DROP FOREIGN KEY FK_24DD10B551F42524');
    $this->addSql('ALTER TABLE werewolf_gift DROP FOREIGN KEY FK_D94BCB96C6EC01BF');
    $this->addSql('ALTER TABLE werewolf_gift DROP FOREIGN KEY FK_D94BCB9697A95A83');
    $this->addSql('DROP TABLE auspice');
    $this->addSql('DROP TABLE auspice_skill');
    $this->addSql('DROP TABLE auspice_gift_list');
    $this->addSql('DROP TABLE auspice_translation');
    $this->addSql('DROP TABLE gift');
    $this->addSql('DROP TABLE gift_list');
    $this->addSql('DROP TABLE gift_list_translation');
    $this->addSql('DROP TABLE gift_translation');
    $this->addSql('DROP TABLE renown');
    $this->addSql('DROP TABLE renown_translation');
    $this->addSql('DROP TABLE tribe');
    $this->addSql('DROP TABLE tribe_gift_list');
    $this->addSql('DROP TABLE tribe_translation');
    $this->addSql('DROP TABLE werewolf_gift');
    $this->addSql('DROP TABLE werewolf_renown');
    $this->addSql('ALTER TABLE werewolf DROP FOREIGN KEY FK_D5FB84ABCDBD8FE6');
    $this->addSql('ALTER TABLE werewolf DROP FOREIGN KEY FK_D5FB84AB6F3EE0AD');
    $this->addSql('ALTER TABLE werewolf DROP FOREIGN KEY FK_D5FB84AB6FF290B8');
    $this->addSql('DROP INDEX IDX_D5FB84ABCDBD8FE6 ON werewolf');
    $this->addSql('DROP INDEX IDX_D5FB84AB6F3EE0AD ON werewolf');
    $this->addSql('DROP INDEX UNIQ_D5FB84AB6FF290B8 ON werewolf');
    $this->addSql('ALTER TABLE werewolf DROP primal_urge, DROP essence, DROP auspice_id, DROP tribe_id, DROP renowns_id');
    $this->addSql('ALTER TABLE auspice DROP emblem');
    $this->addSql('ALTER TABLE auspice DROP ability_name, CHANGE quote quote LONGTEXT NOT NULL');
    $this->addSql('ALTER TABLE tribe DROP FOREIGN KEY FK_2653B5588B777BD8');
    $this->addSql('DROP INDEX IDX_2653B5588B777BD8 ON tribe');
    $this->addSql('ALTER TABLE tribe DROP renown_id');
    $this->addSql('ALTER TABLE gift DROP cost, DROP action, DROP is_contested, DROP contested_text');
  }
}
