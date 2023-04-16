<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206062956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devotion_attribute (devotion_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_E0DFB3255871450E (devotion_id), INDEX IDX_E0DFB325B6E62EFA (attribute_id), PRIMARY KEY(devotion_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devotion_skill (devotion_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_E7AA68A85871450E (devotion_id), INDEX IDX_E7AA68A85585C142 (skill_id), PRIMARY KEY(devotion_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devotion_discipline (devotion_id INT NOT NULL, discipline_id INT NOT NULL, INDEX IDX_14CFB4B05871450E (devotion_id), INDEX IDX_14CFB4B0A5522701 (discipline_id), PRIMARY KEY(devotion_id, discipline_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devotion_attribute ADD CONSTRAINT FK_E0DFB3255871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devotion_attribute ADD CONSTRAINT FK_E0DFB325B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devotion_skill ADD CONSTRAINT FK_E7AA68A85871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devotion_skill ADD CONSTRAINT FK_E7AA68A85585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devotion_discipline ADD CONSTRAINT FK_14CFB4B05871450E FOREIGN KEY (devotion_id) REFERENCES devotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE devotion_discipline ADD CONSTRAINT FK_14CFB4B0A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devotion_attribute DROP FOREIGN KEY FK_E0DFB3255871450E');
        $this->addSql('ALTER TABLE devotion_attribute DROP FOREIGN KEY FK_E0DFB325B6E62EFA');
        $this->addSql('ALTER TABLE devotion_skill DROP FOREIGN KEY FK_E7AA68A85871450E');
        $this->addSql('ALTER TABLE devotion_skill DROP FOREIGN KEY FK_E7AA68A85585C142');
        $this->addSql('ALTER TABLE devotion_discipline DROP FOREIGN KEY FK_14CFB4B05871450E');
        $this->addSql('ALTER TABLE devotion_discipline DROP FOREIGN KEY FK_14CFB4B0A5522701');
        $this->addSql('DROP TABLE devotion_attribute');
        $this->addSql('DROP TABLE devotion_skill');
        $this->addSql('DROP TABLE devotion_discipline');
    }
}
