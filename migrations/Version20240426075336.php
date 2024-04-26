<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426075336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, category_id INT DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE article_synopsis (article_id INT NOT NULL, synopsis_id INT NOT NULL, INDEX IDX_2C220C27294869C (article_id), INDEX IDX_2C220C28E076A45 (synopsis_id), PRIMARY KEY(article_id, synopsis_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE world_building_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(50) NOT NULL, description VARCHAR(400) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_58AEC6EDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES world_building_category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_synopsis ADD CONSTRAINT FK_2C220C27294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_synopsis ADD CONSTRAINT FK_2C220C28E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE world_building_category ADD CONSTRAINT FK_58AEC6EDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article_synopsis DROP FOREIGN KEY FK_2C220C27294869C');
        $this->addSql('ALTER TABLE article_synopsis DROP FOREIGN KEY FK_2C220C28E076A45');
        $this->addSql('ALTER TABLE world_building_category DROP FOREIGN KEY FK_58AEC6EDF675F31B');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_synopsis');
        $this->addSql('DROP TABLE world_building_category');
    }
}
