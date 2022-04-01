<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220330224403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discipline_power (id INT AUTO_INCREMENT NOT NULL, discipline_id INT NOT NULL, attribute_id INT DEFAULT NULL, skill_id INT DEFAULT NULL, details LONGTEXT NOT NULL, INDEX IDX_ECEDB2E8A5522701 (discipline_id), INDEX IDX_ECEDB2E8B6E62EFA (attribute_id), INDEX IDX_ECEDB2E85585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E8A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E8B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E85585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE discipline_power');
    }
}
