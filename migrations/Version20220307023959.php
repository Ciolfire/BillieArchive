<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220307023959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_merit (id INT AUTO_INCREMENT NOT NULL, merit_id INT NOT NULL, character_id INT NOT NULL, choice VARCHAR(255) DEFAULT NULL, details JSON DEFAULT NULL, level SMALLINT NOT NULL, INDEX IDX_E1997D4758D79B5E (merit_id), INDEX IDX_E1997D471136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_merit ADD CONSTRAINT FK_E1997D4758D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id)');
        $this->addSql('ALTER TABLE character_merit ADD CONSTRAINT FK_E1997D471136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters DROP merits, CHANGE age age INT UNSIGNED DEFAULT NULL, CHANGE intelligence intelligence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE wits wits SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE resolve resolve SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE strength strength SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE dexterity dexterity SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE stamina stamina SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE presence presence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE manipulation manipulation SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE composure composure SMALLINT UNSIGNED DEFAULT 1 NOT NULL, CHANGE willpower willpower SMALLINT NOT NULL, CHANGE current_willpower current_willpower SMALLINT NOT NULL, CHANGE moral moral SMALLINT NOT NULL, CHANGE size size SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE character_merit');
        $this->addSql('ALTER TABLE characters ADD merits JSON DEFAULT NULL, CHANGE age age SMALLINT UNSIGNED DEFAULT NULL, CHANGE intelligence intelligence TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE wits wits TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE resolve resolve TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE strength strength TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE dexterity dexterity TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE stamina stamina TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE presence presence TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE manipulation manipulation TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE composure composure TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE willpower willpower TINYINT(1) NOT NULL, CHANGE moral moral TINYINT(1) NOT NULL, CHANGE size size TINYINT(1) NOT NULL, CHANGE current_willpower current_willpower TINYINT(1) NOT NULL');
    }
}
