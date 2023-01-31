<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130154349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E85585C142');
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E8B6E62EFA');
        $this->addSql('DROP INDEX IDX_ECEDB2E85585C142 ON discipline_power');
        $this->addSql('DROP INDEX IDX_ECEDB2E8B6E62EFA ON discipline_power');
        $this->addSql('ALTER TABLE discipline_power DROP attribute_id, DROP skill_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline_power ADD attribute_id INT DEFAULT NULL, ADD skill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E85585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E8B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_ECEDB2E85585C142 ON discipline_power (skill_id)');
        $this->addSql('CREATE INDEX IDX_ECEDB2E8B6E62EFA ON discipline_power (attribute_id)');
    }
}
