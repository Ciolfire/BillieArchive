<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208213623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mage_arcanum (id INT AUTO_INCREMENT NOT NULL, level SMALLINT NOT NULL, mage_id INT NOT NULL, arcanum_id INT NOT NULL, INDEX IDX_E09AD8E5A4F9EEA (mage_id), INDEX IDX_E09AD8E523DF3E0B (arcanum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE organization_order (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE mage_order_skill (mage_order_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_2572B23FFE2BACBC (mage_order_id), INDEX IDX_2572B23F5585C142 (skill_id), PRIMARY KEY(mage_order_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE mage_arcanum ADD CONSTRAINT FK_E09AD8E5A4F9EEA FOREIGN KEY (mage_id) REFERENCES mage (id)');
        $this->addSql('ALTER TABLE mage_arcanum ADD CONSTRAINT FK_E09AD8E523DF3E0B FOREIGN KEY (arcanum_id) REFERENCES arcanum (id)');
        $this->addSql('ALTER TABLE organization_order ADD CONSTRAINT FK_7BAE755FBF396750 FOREIGN KEY (id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mage_order_skill ADD CONSTRAINT FK_2572B23FFE2BACBC FOREIGN KEY (mage_order_id) REFERENCES organization_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mage_order_skill ADD CONSTRAINT FK_2572B23F5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mage ADD gnosis SMALLINT NOT NULL, ADD mana SMALLINT NOT NULL, ADD path_id INT NOT NULL, ADD order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mage ADD CONSTRAINT FK_B6793962D96C566B FOREIGN KEY (path_id) REFERENCES path (id)');
        $this->addSql('ALTER TABLE mage ADD CONSTRAINT FK_B67939628D9F6D38 FOREIGN KEY (order_id) REFERENCES organization_order (id)');
        $this->addSql('CREATE INDEX IDX_B6793962D96C566B ON mage (path_id)');
        $this->addSql('CREATE INDEX IDX_B67939628D9F6D38 ON mage (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_arcanum DROP FOREIGN KEY FK_E09AD8E5A4F9EEA');
        $this->addSql('ALTER TABLE mage_arcanum DROP FOREIGN KEY FK_E09AD8E523DF3E0B');
        $this->addSql('ALTER TABLE organization_order DROP FOREIGN KEY FK_7BAE755FBF396750');
        $this->addSql('ALTER TABLE mage_order_skill DROP FOREIGN KEY FK_2572B23FFE2BACBC');
        $this->addSql('ALTER TABLE mage_order_skill DROP FOREIGN KEY FK_2572B23F5585C142');
        $this->addSql('DROP TABLE mage_arcanum');
        $this->addSql('DROP TABLE organization_order');
        $this->addSql('DROP TABLE mage_order_skill');
        $this->addSql('ALTER TABLE mage DROP FOREIGN KEY FK_B6793962D96C566B');
        $this->addSql('ALTER TABLE mage DROP FOREIGN KEY FK_B67939628D9F6D38');
        $this->addSql('DROP INDEX IDX_B6793962D96C566B ON mage');
        $this->addSql('DROP INDEX IDX_B67939628D9F6D38 ON mage');
        $this->addSql('ALTER TABLE mage DROP gnosis, DROP mana, DROP path_id, DROP order_id');
    }
}
