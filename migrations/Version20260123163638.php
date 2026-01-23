<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260123163638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_weapon_thrown (is_aerodynamic TINYINT(1) NOT NULL, id INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE item_weapon_thrown ADD CONSTRAINT FK_11A31260BF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_weapon_ranged DROP damage, DROP special, DROP damage_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_weapon_thrown DROP FOREIGN KEY FK_11A31260BF396750');
        $this->addSql('DROP TABLE item_weapon_thrown');
        $this->addSql('ALTER TABLE item_weapon_ranged ADD damage SMALLINT NOT NULL, ADD special LONGTEXT NOT NULL, ADD damage_type SMALLINT NOT NULL');
    }
}
