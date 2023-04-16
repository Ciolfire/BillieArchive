<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214185415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note_category (id INT AUTO_INCREMENT NOT NULL, chronicle_id INT DEFAULT NULL, user_id INT NOT NULL, INDEX IDX_1617C55F237D532E (chronicle_id), INDEX IDX_1617C55FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_category ADD CONSTRAINT FK_1617C55F237D532E FOREIGN KEY (chronicle_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE note_category ADD CONSTRAINT FK_1617C55FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note ADD category_id INT DEFAULT NULL, DROP category');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1412469DE2 FOREIGN KEY (category_id) REFERENCES note_category (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA1412469DE2 ON note (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1412469DE2');
        $this->addSql('ALTER TABLE note_category DROP FOREIGN KEY FK_1617C55F237D532E');
        $this->addSql('ALTER TABLE note_category DROP FOREIGN KEY FK_1617C55FA76ED395');
        $this->addSql('DROP TABLE note_category');
        $this->addSql('DROP INDEX IDX_CFBDFA1412469DE2 ON note');
        $this->addSql('ALTER TABLE note ADD category VARCHAR(255) DEFAULT NULL, DROP category_id');
    }
}
