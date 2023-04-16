<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230201012318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE merit_prerequisite (merit_id INT NOT NULL, prerequisite_id INT NOT NULL, INDEX IDX_7BC20DCF58D79B5E (merit_id), INDEX IDX_7BC20DCF276AF86B (prerequisite_id), PRIMARY KEY(merit_id, prerequisite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merit_prerequisite ADD CONSTRAINT FK_7BC20DCF58D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE merit_prerequisite ADD CONSTRAINT FK_7BC20DCF276AF86B FOREIGN KEY (prerequisite_id) REFERENCES prerequisite (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merit_prerequisite DROP FOREIGN KEY FK_7BC20DCF58D79B5E');
        $this->addSql('ALTER TABLE merit_prerequisite DROP FOREIGN KEY FK_7BC20DCF276AF86B');
        $this->addSql('DROP TABLE merit_prerequisite');
    }
}
