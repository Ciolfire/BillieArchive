<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213234047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, chronicle_id INT DEFAULT NULL, player_id INT DEFAULT NULL, character_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, category VARCHAR(255) DEFAULT NULL, INDEX IDX_CFBDFA14237D532E (chronicle_id), INDEX IDX_CFBDFA1499E6F5DF (player_id), INDEX IDX_CFBDFA141136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14237D532E FOREIGN KEY (chronicle_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1499E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA141136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters CHANGE name name VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14237D532E');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1499E6F5DF');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA141136BE75');
        $this->addSql('DROP TABLE note');
        $this->addSql('ALTER TABLE characters CHANGE name name VARCHAR(50) DEFAULT NULL');
    }
}
