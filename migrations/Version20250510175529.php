<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510175529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE possessed_possessed_vestment (possessed_id INT NOT NULL, possessed_vestment_id INT NOT NULL, INDEX IDX_379B5EF3FC487BA3 (possessed_id), INDEX IDX_379B5EF3551AF7D (possessed_vestment_id), PRIMARY KEY(possessed_id, possessed_vestment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE possessed_vestment (id INT AUTO_INCREMENT NOT NULL, level SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, effect LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, vice_id INT NOT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_EB782D1176457273 (vice_id), INDEX IDX_EB782D117B02D7EA (homebrew_for_id), INDEX IDX_EB782D1116A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE possessed_vestment_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX possessed_vestment_translation_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE possessed_vice (id INT AUTO_INCREMENT NOT NULL, level SMALLINT NOT NULL, vice_id INT NOT NULL, possessed_id INT NOT NULL, INDEX IDX_708ED00076457273 (vice_id), INDEX IDX_708ED000FC487BA3 (possessed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment ADD CONSTRAINT FK_379B5EF3FC487BA3 FOREIGN KEY (possessed_id) REFERENCES possessed (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment ADD CONSTRAINT FK_379B5EF3551AF7D FOREIGN KEY (possessed_vestment_id) REFERENCES possessed_vestment (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vestment ADD CONSTRAINT FK_EB782D1176457273 FOREIGN KEY (vice_id) REFERENCES vice (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vestment ADD CONSTRAINT FK_EB782D117B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vestment ADD CONSTRAINT FK_EB782D1116A2B381 FOREIGN KEY (book_id) REFERENCES book (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice ADD CONSTRAINT FK_708ED00076457273 FOREIGN KEY (vice_id) REFERENCES vice (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice ADD CONSTRAINT FK_708ED000FC487BA3 FOREIGN KEY (possessed_id) REFERENCES possessed (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed ADD infernal_will SMALLINT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_effect ADD possessed_vestment_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_effect ADD CONSTRAINT FK_B2A39BF551AF7D FOREIGN KEY (possessed_vestment_id) REFERENCES possessed_vestment (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B2A39BF551AF7D ON status_effect (possessed_vestment_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment DROP FOREIGN KEY FK_379B5EF3FC487BA3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment DROP FOREIGN KEY FK_379B5EF3551AF7D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vestment DROP FOREIGN KEY FK_EB782D1176457273
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vestment DROP FOREIGN KEY FK_EB782D117B02D7EA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vestment DROP FOREIGN KEY FK_EB782D1116A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice DROP FOREIGN KEY FK_708ED00076457273
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice DROP FOREIGN KEY FK_708ED000FC487BA3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE possessed_possessed_vestment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE possessed_vestment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE possessed_vestment_translation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE possessed_vice
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed DROP infernal_will
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_effect DROP FOREIGN KEY FK_B2A39BF551AF7D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B2A39BF551AF7D ON status_effect
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status_effect DROP possessed_vestment_id
        SQL);
    }
}
