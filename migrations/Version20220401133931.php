<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401133931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characters_attributes (id INT AUTO_INCREMENT NOT NULL, intelligence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, wits SMALLINT UNSIGNED DEFAULT 1 NOT NULL, resolve SMALLINT UNSIGNED DEFAULT 1 NOT NULL, strength SMALLINT UNSIGNED DEFAULT 1 NOT NULL, dexterity SMALLINT UNSIGNED DEFAULT 1 NOT NULL, stamina SMALLINT UNSIGNED DEFAULT 1 NOT NULL, presence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, manipulation SMALLINT UNSIGNED DEFAULT 1 NOT NULL, composure SMALLINT UNSIGNED DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characters ADD attributes_id INT DEFAULT NULL, DROP intelligence, DROP wits, DROP resolve, DROP strength, DROP dexterity, DROP stamina, DROP presence, DROP manipulation, DROP composure');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EBAAF4009 FOREIGN KEY (attributes_id) REFERENCES characters_attributes (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3A29410EBAAF4009 ON characters (attributes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EBAAF4009');
        $this->addSql('DROP TABLE characters_attributes');
        $this->addSql('DROP INDEX UNIQ_3A29410EBAAF4009 ON characters');
        $this->addSql('ALTER TABLE characters ADD intelligence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD wits SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD resolve SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD strength SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD dexterity SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD stamina SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD presence SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD manipulation SMALLINT UNSIGNED DEFAULT 1 NOT NULL, ADD composure SMALLINT UNSIGNED DEFAULT 1 NOT NULL, DROP attributes_id');
    }
}
