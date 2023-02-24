<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224004530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rolls_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX rolls_translation_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rule (id INT AUTO_INCREMENT NOT NULL, parent_rule_id INT DEFAULT NULL, book_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, details LONGTEXT NOT NULL, type VARCHAR(255) DEFAULT NULL, page SMALLINT DEFAULT NULL, INDEX IDX_46D8ACCC6067717 (parent_rule_id), INDEX IDX_46D8ACCC16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCC6067717 FOREIGN KEY (parent_rule_id) REFERENCES rule (id)');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCC16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE derangement ADD book_id INT DEFAULT NULL, ADD homebrew_for_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE derangement ADD CONSTRAINT FK_CABCF56A16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE derangement ADD CONSTRAINT FK_CABCF56A7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_CABCF56A16A2B381 ON derangement (book_id)');
        $this->addSql('CREATE INDEX IDX_CABCF56A7B02D7EA ON derangement (homebrew_for_id)');
        $this->addSql('ALTER TABLE roll ADD book_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE roll ADD CONSTRAINT FK_2EB532CE16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_2EB532CE16A2B381 ON roll (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCC6067717');
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCC16A2B381');
        $this->addSql('DROP TABLE rolls_translations');
        $this->addSql('DROP TABLE rule');
        $this->addSql('ALTER TABLE derangement DROP FOREIGN KEY FK_CABCF56A16A2B381');
        $this->addSql('ALTER TABLE derangement DROP FOREIGN KEY FK_CABCF56A7B02D7EA');
        $this->addSql('DROP INDEX IDX_CABCF56A16A2B381 ON derangement');
        $this->addSql('DROP INDEX IDX_CABCF56A7B02D7EA ON derangement');
        $this->addSql('ALTER TABLE derangement DROP book_id, DROP homebrew_for_id, DROP page');
        $this->addSql('ALTER TABLE roll DROP FOREIGN KEY FK_2EB532CE16A2B381');
        $this->addSql('DROP INDEX IDX_2EB532CE16A2B381 ON roll');
        $this->addSql('ALTER TABLE roll DROP book_id, DROP page');
    }
}
