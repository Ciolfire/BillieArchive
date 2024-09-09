<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240909004128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE derangement ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE derangement ADD CONSTRAINT FK_CABCF56AC54C8C93 FOREIGN KEY (type_id) REFERENCES content_type (id)');
        $this->addSql('CREATE INDEX IDX_CABCF56AC54C8C93 ON derangement (type_id)');
        $this->addSql('ALTER TABLE roll ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE roll ADD CONSTRAINT FK_2EB532CEC54C8C93 FOREIGN KEY (type_id) REFERENCES content_type (id)');
        $this->addSql('CREATE INDEX IDX_2EB532CEC54C8C93 ON roll (type_id)');
        $this->addSql('ALTER TABLE rule ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCCC54C8C93 FOREIGN KEY (type_id) REFERENCES content_type (id)');
        $this->addSql('CREATE INDEX IDX_46D8ACCCC54C8C93 ON rule (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE derangement DROP FOREIGN KEY FK_CABCF56AC54C8C93');
        $this->addSql('DROP INDEX IDX_CABCF56AC54C8C93 ON derangement');
        $this->addSql('ALTER TABLE derangement DROP type_id');
        $this->addSql('ALTER TABLE roll DROP FOREIGN KEY FK_2EB532CEC54C8C93');
        $this->addSql('DROP INDEX IDX_2EB532CEC54C8C93 ON roll');
        $this->addSql('ALTER TABLE roll DROP type_id');
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCCC54C8C93');
        $this->addSql('DROP INDEX IDX_46D8ACCCC54C8C93 ON rule');
        $this->addSql('ALTER TABLE rule DROP type_id');
    }
}
