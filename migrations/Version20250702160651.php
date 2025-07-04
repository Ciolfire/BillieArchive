<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702160651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE mage_spell_rote (mage_id INT NOT NULL, spell_rote_id INT NOT NULL, INDEX IDX_58859BBFA4F9EEA (mage_id), INDEX IDX_58859BBFEBF6BBF3 (spell_rote_id), PRIMARY KEY(mage_id, spell_rote_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mage_spell_rote ADD CONSTRAINT FK_58859BBFA4F9EEA FOREIGN KEY (mage_id) REFERENCES mage (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mage_spell_rote ADD CONSTRAINT FK_58859BBFEBF6BBF3 FOREIGN KEY (spell_rote_id) REFERENCES spell_rote (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE spell_rote ADD creator_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE spell_rote ADD CONSTRAINT FK_EB09476261220EA6 FOREIGN KEY (creator_id) REFERENCES mage (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EB09476261220EA6 ON spell_rote (creator_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE mage_spell_rote DROP FOREIGN KEY FK_58859BBFA4F9EEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mage_spell_rote DROP FOREIGN KEY FK_58859BBFEBF6BBF3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mage_spell_rote
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE spell_rote DROP FOREIGN KEY FK_EB09476261220EA6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EB09476261220EA6 ON spell_rote
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE spell_rote DROP creator_id
        SQL);
    }
}
