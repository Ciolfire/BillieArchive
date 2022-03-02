<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301002411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD virtue_id INT DEFAULT NULL, ADD vice_id INT DEFAULT NULL, DROP virtue, DROP vice');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410EE59AEF18 FOREIGN KEY (virtue_id) REFERENCES virtue (id)');
        $this->addSql('ALTER TABLE characters ADD CONSTRAINT FK_3A29410E76457273 FOREIGN KEY (vice_id) REFERENCES vice (id)');
        $this->addSql('CREATE INDEX IDX_3A29410EE59AEF18 ON characters (virtue_id)');
        $this->addSql('CREATE INDEX IDX_3A29410E76457273 ON characters (vice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410EE59AEF18');
        $this->addSql('ALTER TABLE characters DROP FOREIGN KEY FK_3A29410E76457273');
        $this->addSql('DROP INDEX IDX_3A29410EE59AEF18 ON characters');
        $this->addSql('DROP INDEX IDX_3A29410E76457273 ON characters');
        $this->addSql('ALTER TABLE characters ADD virtue VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD vice VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP virtue_id, DROP vice_id');
    }
}
