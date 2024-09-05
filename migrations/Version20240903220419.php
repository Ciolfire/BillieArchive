<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903220419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE path (id INT AUTO_INCREMENT NOT NULL, attribute_id INT NOT NULL, inferior_arcanum_id INT NOT NULL, name VARCHAR(40) NOT NULL, description LONGTEXT NOT NULL, nimbus LONGTEXT NOT NULL, INDEX IDX_B548B0FB6E62EFA (attribute_id), INDEX IDX_B548B0F4058D00B (inferior_arcanum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE path_arcanum (path_id INT NOT NULL, arcanum_id INT NOT NULL, INDEX IDX_4FFBE94BD96C566B (path_id), INDEX IDX_4FFBE94B23DF3E0B (arcanum_id), PRIMARY KEY(path_id, arcanum_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F4058D00B FOREIGN KEY (inferior_arcanum_id) REFERENCES arcanum (id)');
        $this->addSql('ALTER TABLE path_arcanum ADD CONSTRAINT FK_4FFBE94BD96C566B FOREIGN KEY (path_id) REFERENCES path (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE path_arcanum ADD CONSTRAINT FK_4FFBE94B23DF3E0B FOREIGN KEY (arcanum_id) REFERENCES arcanum (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0FB6E62EFA');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0F4058D00B');
        $this->addSql('ALTER TABLE path_arcanum DROP FOREIGN KEY FK_4FFBE94BD96C566B');
        $this->addSql('ALTER TABLE path_arcanum DROP FOREIGN KEY FK_4FFBE94B23DF3E0B');
        $this->addSql('DROP TABLE path');
        $this->addSql('DROP TABLE path_arcanum');
    }
}
