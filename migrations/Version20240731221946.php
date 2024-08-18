<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731221946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle (id INT NOT NULL, acceleration SMALLINT NOT NULL, safe_speed INT NOT NULL, max_speed SMALLINT NOT NULL, handling VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486BF396750 FOREIGN KEY (id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP is_weapon');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486BF396750');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('ALTER TABLE item ADD is_weapon TINYINT(1) NOT NULL');
    }
}
