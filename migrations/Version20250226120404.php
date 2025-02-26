<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226120404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blood_bath (id INT AUTO_INCREMENT NOT NULL, bath JSON NOT NULL, blood JSON NOT NULL, effects JSON NOT NULL, frequency JSON NOT NULL, preparation JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blood_bather ADD bath_id INT NOT NULL');
        $this->addSql('ALTER TABLE blood_bather ADD CONSTRAINT FK_220EC49D292DDB8C FOREIGN KEY (bath_id) REFERENCES blood_bath (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_220EC49D292DDB8C ON blood_bather (bath_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE blood_bath');
        $this->addSql('ALTER TABLE blood_bather DROP FOREIGN KEY FK_220EC49D292DDB8C');
        $this->addSql('DROP INDEX UNIQ_220EC49D292DDB8C ON blood_bather');
        $this->addSql('ALTER TABLE blood_bather DROP bath_id');
    }
}
