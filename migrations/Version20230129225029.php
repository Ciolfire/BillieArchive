<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129225029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discipline_power_attribute (discipline_power_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_299417ABC9F8163B (discipline_power_id), INDEX IDX_299417ABB6E62EFA (attribute_id), PRIMARY KEY(discipline_power_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discipline_power_skill (discipline_power_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_C0F87A51C9F8163B (discipline_power_id), INDEX IDX_C0F87A515585C142 (skill_id), PRIMARY KEY(discipline_power_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prerequisite (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, entity_id INT NOT NULL, value SMALLINT NOT NULL, is_mandatory TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE discipline_power_attribute ADD CONSTRAINT FK_299417ABC9F8163B FOREIGN KEY (discipline_power_id) REFERENCES discipline_power (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discipline_power_attribute ADD CONSTRAINT FK_299417ABB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discipline_power_skill ADD CONSTRAINT FK_C0F87A51C9F8163B FOREIGN KEY (discipline_power_id) REFERENCES discipline_power (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discipline_power_skill ADD CONSTRAINT FK_C0F87A515585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline_power_attribute DROP FOREIGN KEY FK_299417ABC9F8163B');
        $this->addSql('ALTER TABLE discipline_power_attribute DROP FOREIGN KEY FK_299417ABB6E62EFA');
        $this->addSql('ALTER TABLE discipline_power_skill DROP FOREIGN KEY FK_C0F87A51C9F8163B');
        $this->addSql('ALTER TABLE discipline_power_skill DROP FOREIGN KEY FK_C0F87A515585C142');
        $this->addSql('DROP TABLE discipline_power_attribute');
        $this->addSql('DROP TABLE discipline_power_skill');
        $this->addSql('DROP TABLE prerequisite');
    }
}
