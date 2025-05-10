<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509130027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attainment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level SMALLINT NOT NULL, description LONGTEXT NOT NULL, legacy_id INT NOT NULL, INDEX IDX_57840F7D184998FC (legacy_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE legacy (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, nickname VARCHAR(255) NOT NULL, short LONGTEXT NOT NULL, emblem VARCHAR(255) DEFAULT NULL, quote VARCHAR(255) DEFAULT NULL, page SMALLINT DEFAULT NULL, path_id INT DEFAULT NULL, parent_order_id INT DEFAULT NULL, arcanum_id INT NOT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_17CB09EED96C566B (path_id), INDEX IDX_17CB09EE1252C1E9 (parent_order_id), INDEX IDX_17CB09EE23DF3E0B (arcanum_id), INDEX IDX_17CB09EE16A2B381 (book_id), INDEX IDX_17CB09EE7B02D7EA (homebrew_for_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE attainment ADD CONSTRAINT FK_57840F7D184998FC FOREIGN KEY (legacy_id) REFERENCES legacy (id)');
        $this->addSql('ALTER TABLE legacy ADD CONSTRAINT FK_17CB09EED96C566B FOREIGN KEY (path_id) REFERENCES path (id)');
        $this->addSql('ALTER TABLE legacy ADD CONSTRAINT FK_17CB09EE1252C1E9 FOREIGN KEY (parent_order_id) REFERENCES organization_order (id)');
        $this->addSql('ALTER TABLE legacy ADD CONSTRAINT FK_17CB09EE23DF3E0B FOREIGN KEY (arcanum_id) REFERENCES arcanum (id)');
        $this->addSql('ALTER TABLE legacy ADD CONSTRAINT FK_17CB09EE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE legacy ADD CONSTRAINT FK_17CB09EE7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attainment DROP FOREIGN KEY FK_57840F7D184998FC');
        $this->addSql('ALTER TABLE legacy DROP FOREIGN KEY FK_17CB09EED96C566B');
        $this->addSql('ALTER TABLE legacy DROP FOREIGN KEY FK_17CB09EE1252C1E9');
        $this->addSql('ALTER TABLE legacy DROP FOREIGN KEY FK_17CB09EE23DF3E0B');
        $this->addSql('ALTER TABLE legacy DROP FOREIGN KEY FK_17CB09EE16A2B381');
        $this->addSql('ALTER TABLE legacy DROP FOREIGN KEY FK_17CB09EE7B02D7EA');
        $this->addSql('DROP TABLE attainment');
        $this->addSql('DROP TABLE legacy');
    }
}
