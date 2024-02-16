<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216130944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2500) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_64C19C1F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2500) DEFAULT NULL, position INT NOT NULL, synopsis_id INT NOT NULL, INDEX IDX_F981B52E8E076A45 (synopsis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(1500) DEFAULT NULL, color VARCHAR(30) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, valid TINYINT(1) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, synopsis_id INT NOT NULL, chapter_id INT DEFAULT NULL, INDEX IDX_DDAA1CDA8E076A45 (synopsis_id), INDEX IDX_DDAA1CDA579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, pitch VARCHAR(3000) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_572AD4A9F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis_category (synopsis_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_989A4E6D8E076A45 (synopsis_id), INDEX IDX_989A4E6D12469DE2 (category_id), PRIMARY KEY(synopsis_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E8E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA8E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE synopsis ADD CONSTRAINT FK_572AD4A9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE synopsis_category ADD CONSTRAINT FK_989A4E6D8E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_category ADD CONSTRAINT FK_989A4E6D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1F675F31B');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E8E076A45');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA8E076A45');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA579F4768');
        $this->addSql('ALTER TABLE synopsis DROP FOREIGN KEY FK_572AD4A9F675F31B');
        $this->addSql('ALTER TABLE synopsis_category DROP FOREIGN KEY FK_989A4E6D8E076A45');
        $this->addSql('ALTER TABLE synopsis_category DROP FOREIGN KEY FK_989A4E6D12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE synopsis');
        $this->addSql('DROP TABLE synopsis_category');
    }
}
