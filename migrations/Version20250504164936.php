<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250504164936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mage_spell_arcanum (id INT AUTO_INCREMENT NOT NULL, level SMALLINT NOT NULL, choice_group SMALLINT DEFAULT NULL, is_optional TINYINT(1) NOT NULL, spell_id INT NOT NULL, arcanum_id INT NOT NULL, INDEX IDX_A9FA79B6479EC90D (spell_id), INDEX IDX_A9FA79B623DF3E0B (arcanum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE mage_spell_arcanum ADD CONSTRAINT FK_A9FA79B6479EC90D FOREIGN KEY (spell_id) REFERENCES mage_spell (id)');
        $this->addSql('ALTER TABLE mage_spell_arcanum ADD CONSTRAINT FK_A9FA79B623DF3E0B FOREIGN KEY (arcanum_id) REFERENCES arcanum (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_spell_arcanum DROP FOREIGN KEY FK_A9FA79B6479EC90D');
        $this->addSql('ALTER TABLE mage_spell_arcanum DROP FOREIGN KEY FK_A9FA79B623DF3E0B');
        $this->addSql('DROP TABLE mage_spell_arcanum');
    }
}
