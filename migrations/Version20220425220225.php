<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425220225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan ADD book_id INT DEFAULT NULL, ADD is_bloodline TINYINT(1) NOT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE clan ADD CONSTRAINT FK_9FF6A30C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_9FF6A30C16A2B381 ON clan (book_id)');
        $this->addSql('ALTER TABLE discipline ADD book_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline ADD CONSTRAINT FK_75BEEE3F16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_75BEEE3F16A2B381 ON discipline (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan DROP FOREIGN KEY FK_9FF6A30C16A2B381');
        $this->addSql('DROP INDEX IDX_9FF6A30C16A2B381 ON clan');
        $this->addSql('ALTER TABLE clan DROP book_id, DROP is_bloodline, DROP page');
        $this->addSql('ALTER TABLE discipline DROP FOREIGN KEY FK_75BEEE3F16A2B381');
        $this->addSql('DROP INDEX IDX_75BEEE3F16A2B381 ON discipline');
        $this->addSql('ALTER TABLE discipline DROP book_id, DROP page');
    }
}
