<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910175157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covenant_discipline (covenant_id INT NOT NULL, discipline_id INT NOT NULL, INDEX IDX_934A602FDA91032A (covenant_id), INDEX IDX_934A602FA5522701 (discipline_id), PRIMARY KEY(covenant_id, discipline_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE covenant_devotion (covenant_id INT NOT NULL, devotion_id INT NOT NULL, INDEX IDX_C5E82772DA91032A (covenant_id), INDEX IDX_C5E827725871450E (devotion_id), PRIMARY KEY(covenant_id, devotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE covenant_merit (covenant_id INT NOT NULL, merit_id INT NOT NULL, INDEX IDX_9FA0F2BFDA91032A (covenant_id), INDEX IDX_9FA0F2BF58D79B5E (merit_id), PRIMARY KEY(covenant_id, merit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covenant_discipline ADD CONSTRAINT FK_934A602FDA91032A FOREIGN KEY (covenant_id) REFERENCES organization_covenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covenant_discipline ADD CONSTRAINT FK_934A602FA5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covenant_devotion ADD CONSTRAINT FK_C5E82772DA91032A FOREIGN KEY (covenant_id) REFERENCES organization_covenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covenant_devotion ADD CONSTRAINT FK_C5E827725871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covenant_merit ADD CONSTRAINT FK_9FA0F2BFDA91032A FOREIGN KEY (covenant_id) REFERENCES organization_covenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covenant_merit ADD CONSTRAINT FK_9FA0F2BF58D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covenant_discipline DROP FOREIGN KEY FK_934A602FDA91032A');
        $this->addSql('ALTER TABLE covenant_discipline DROP FOREIGN KEY FK_934A602FA5522701');
        $this->addSql('ALTER TABLE covenant_devotion DROP FOREIGN KEY FK_C5E82772DA91032A');
        $this->addSql('ALTER TABLE covenant_devotion DROP FOREIGN KEY FK_C5E827725871450E');
        $this->addSql('ALTER TABLE covenant_merit DROP FOREIGN KEY FK_9FA0F2BFDA91032A');
        $this->addSql('ALTER TABLE covenant_merit DROP FOREIGN KEY FK_9FA0F2BF58D79B5E');
        $this->addSql('DROP TABLE covenant_discipline');
        $this->addSql('DROP TABLE covenant_devotion');
        $this->addSql('DROP TABLE covenant_merit');
    }
}
