<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250512003954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE possessed_vice_possessed_vestment (possessed_vice_id INT NOT NULL, possessed_vestment_id INT NOT NULL, INDEX IDX_55352041ED79AE78 (possessed_vice_id), INDEX IDX_55352041551AF7D (possessed_vestment_id), PRIMARY KEY(possessed_vice_id, possessed_vestment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice_possessed_vestment ADD CONSTRAINT FK_55352041ED79AE78 FOREIGN KEY (possessed_vice_id) REFERENCES possessed_vice (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice_possessed_vestment ADD CONSTRAINT FK_55352041551AF7D FOREIGN KEY (possessed_vestment_id) REFERENCES possessed_vestment (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment DROP FOREIGN KEY FK_379B5EF3551AF7D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment DROP FOREIGN KEY FK_379B5EF3FC487BA3
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE possessed_possessed_vestment
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE possessed_possessed_vestment (possessed_id INT NOT NULL, possessed_vestment_id INT NOT NULL, INDEX IDX_379B5EF3551AF7D (possessed_vestment_id), INDEX IDX_379B5EF3FC487BA3 (possessed_id), PRIMARY KEY(possessed_id, possessed_vestment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment ADD CONSTRAINT FK_379B5EF3551AF7D FOREIGN KEY (possessed_vestment_id) REFERENCES possessed_vestment (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_possessed_vestment ADD CONSTRAINT FK_379B5EF3FC487BA3 FOREIGN KEY (possessed_id) REFERENCES possessed (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice_possessed_vestment DROP FOREIGN KEY FK_55352041ED79AE78
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE possessed_vice_possessed_vestment DROP FOREIGN KEY FK_55352041551AF7D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE possessed_vice_possessed_vestment
        SQL);
    }
}
