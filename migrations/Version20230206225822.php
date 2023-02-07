<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206225822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vampire_devotion (vampire_id INT NOT NULL, devotion_id INT NOT NULL, INDEX IDX_33C84F7DCD529B21 (vampire_id), INDEX IDX_33C84F7D5871450E (devotion_id), PRIMARY KEY(vampire_id, devotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vampire_devotion ADD CONSTRAINT FK_33C84F7DCD529B21 FOREIGN KEY (vampire_id) REFERENCES vampire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vampire_devotion ADD CONSTRAINT FK_33C84F7D5871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vampire_devotion DROP FOREIGN KEY FK_33C84F7DCD529B21');
        $this->addSql('ALTER TABLE vampire_devotion DROP FOREIGN KEY FK_33C84F7D5871450E');
        $this->addSql('DROP TABLE vampire_devotion');
    }
}
