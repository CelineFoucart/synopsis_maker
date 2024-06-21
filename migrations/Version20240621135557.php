<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621135557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, link VARCHAR(255) DEFAULT NULL, category_id INT DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE article_synopsis (article_id INT NOT NULL, synopsis_id INT NOT NULL, INDEX IDX_2C220C27294869C (article_id), INDEX IDX_2C220C28E076A45 (synopsis_id), PRIMARY KEY(article_id, synopsis_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2500) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_64C19C1F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(2500) DEFAULT NULL, position INT NOT NULL, color VARCHAR(30) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, synopsis_id INT NOT NULL, INDEX IDX_F981B52E8E076A45 (synopsis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(2500) DEFAULT NULL, appearance LONGTEXT DEFAULT NULL, biography LONGTEXT DEFAULT NULL, personality JSON DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, job VARCHAR(255) DEFAULT NULL, birthday VARCHAR(255) DEFAULT NULL, birthday_place VARCHAR(255) DEFAULT NULL, death_date VARCHAR(255) DEFAULT NULL, death_place VARCHAR(255) DEFAULT NULL, parents VARCHAR(255) DEFAULT NULL, children VARCHAR(255) DEFAULT NULL, siblings VARCHAR(255) DEFAULT NULL, partner VARCHAR(255) DEFAULT NULL, species VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, role LONGTEXT DEFAULT NULL, complementary LONGTEXT DEFAULT NULL, faction VARCHAR(255) DEFAULT NULL, membership VARCHAR(255) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_937AB034F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(1500) DEFAULT NULL, color VARCHAR(30) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, position INT NOT NULL, valid TINYINT(1) DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, synopsis_id INT NOT NULL, chapter_id INT DEFAULT NULL, INDEX IDX_DDAA1CDA8E076A45 (synopsis_id), INDEX IDX_DDAA1CDA579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE episode_place (episode_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_4F17A599362B62A0 (episode_id), INDEX IDX_4F17A599DA6A219 (place_id), PRIMARY KEY(episode_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE episode_character (episode_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_2DB8260D362B62A0 (episode_id), INDEX IDX_2DB8260D1136BE75 (character_id), PRIMARY KEY(episode_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, username VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, level VARCHAR(255) NOT NULL, action VARCHAR(50) NOT NULL, object VARCHAR(100) NOT NULL, message LONGTEXT NOT NULL, trace LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, role VARCHAR(15000) DEFAULT NULL, description LONGTEXT DEFAULT NULL, complementary LONGTEXT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_741D53CDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, about LONGTEXT DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, rank VARCHAR(255) DEFAULT NULL, interests VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, pitch VARCHAR(3000) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, archived TINYINT(1) DEFAULT NULL, legend VARCHAR(1500) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, tasks JSON DEFAULT NULL, worldbuilding_home LONGTEXT DEFAULT NULL, settings JSON DEFAULT NULL, public TINYINT(1) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_572AD4A9F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis_category (synopsis_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_989A4E6D8E076A45 (synopsis_id), INDEX IDX_989A4E6D12469DE2 (category_id), PRIMARY KEY(synopsis_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis_place (synopsis_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_1A3F51818E076A45 (synopsis_id), INDEX IDX_1A3F5181DA6A219 (place_id), PRIMARY KEY(synopsis_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis_character (synopsis_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_4C8489A08E076A45 (synopsis_id), INDEX IDX_4C8489A01136BE75 (character_id), PRIMARY KEY(synopsis_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, profile_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE world_building_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(50) NOT NULL, description VARCHAR(400) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_58AEC6EDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES world_building_category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_synopsis ADD CONSTRAINT FK_2C220C27294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_synopsis ADD CONSTRAINT FK_2C220C28E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52E8E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id)');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA8E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE episode_place ADD CONSTRAINT FK_4F17A599362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_place ADD CONSTRAINT FK_4F17A599DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D1136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE synopsis ADD CONSTRAINT FK_572AD4A9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE synopsis_category ADD CONSTRAINT FK_989A4E6D8E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_category ADD CONSTRAINT FK_989A4E6D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_place ADD CONSTRAINT FK_1A3F51818E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_place ADD CONSTRAINT FK_1A3F5181DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_character ADD CONSTRAINT FK_4C8489A08E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_character ADD CONSTRAINT FK_4C8489A01136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE world_building_category ADD CONSTRAINT FK_58AEC6EDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article_synopsis DROP FOREIGN KEY FK_2C220C27294869C');
        $this->addSql('ALTER TABLE article_synopsis DROP FOREIGN KEY FK_2C220C28E076A45');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1F675F31B');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52E8E076A45');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034F675F31B');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA8E076A45');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA579F4768');
        $this->addSql('ALTER TABLE episode_place DROP FOREIGN KEY FK_4F17A599362B62A0');
        $this->addSql('ALTER TABLE episode_place DROP FOREIGN KEY FK_4F17A599DA6A219');
        $this->addSql('ALTER TABLE episode_character DROP FOREIGN KEY FK_2DB8260D362B62A0');
        $this->addSql('ALTER TABLE episode_character DROP FOREIGN KEY FK_2DB8260D1136BE75');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDF675F31B');
        $this->addSql('ALTER TABLE synopsis DROP FOREIGN KEY FK_572AD4A9F675F31B');
        $this->addSql('ALTER TABLE synopsis_category DROP FOREIGN KEY FK_989A4E6D8E076A45');
        $this->addSql('ALTER TABLE synopsis_category DROP FOREIGN KEY FK_989A4E6D12469DE2');
        $this->addSql('ALTER TABLE synopsis_place DROP FOREIGN KEY FK_1A3F51818E076A45');
        $this->addSql('ALTER TABLE synopsis_place DROP FOREIGN KEY FK_1A3F5181DA6A219');
        $this->addSql('ALTER TABLE synopsis_character DROP FOREIGN KEY FK_4C8489A08E076A45');
        $this->addSql('ALTER TABLE synopsis_character DROP FOREIGN KEY FK_4C8489A01136BE75');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE world_building_category DROP FOREIGN KEY FK_58AEC6EDF675F31B');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_synopsis');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE episode_place');
        $this->addSql('DROP TABLE episode_character');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE synopsis');
        $this->addSql('DROP TABLE synopsis_category');
        $this->addSql('DROP TABLE synopsis_place');
        $this->addSql('DROP TABLE synopsis_character');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE world_building_category');
    }
}
