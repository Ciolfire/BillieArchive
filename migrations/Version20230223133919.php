<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223133919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE roll (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, action SMALLINT NOT NULL, details LONGTEXT NOT NULL, is_important TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roll_attribute (roll_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_D0C302A2AB0B6D26 (roll_id), INDEX IDX_D0C302A2B6E62EFA (attribute_id), PRIMARY KEY(roll_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roll_skill (roll_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_3C79498BAB0B6D26 (roll_id), INDEX IDX_3C79498B5585C142 (skill_id), PRIMARY KEY(roll_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE roll_attribute ADD CONSTRAINT FK_D0C302A2AB0B6D26 FOREIGN KEY (roll_id) REFERENCES roll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE roll_attribute ADD CONSTRAINT FK_D0C302A2B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE roll_skill ADD CONSTRAINT FK_3C79498BAB0B6D26 FOREIGN KEY (roll_id) REFERENCES roll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE roll_skill ADD CONSTRAINT FK_3C79498B5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roll_attribute DROP FOREIGN KEY FK_D0C302A2AB0B6D26');
        $this->addSql('ALTER TABLE roll_attribute DROP FOREIGN KEY FK_D0C302A2B6E62EFA');
        $this->addSql('ALTER TABLE roll_skill DROP FOREIGN KEY FK_3C79498BAB0B6D26');
        $this->addSql('ALTER TABLE roll_skill DROP FOREIGN KEY FK_3C79498B5585C142');
        $this->addSql('DROP TABLE roll');
        $this->addSql('DROP TABLE roll_attribute');
        $this->addSql('DROP TABLE roll_skill');
    }
}
