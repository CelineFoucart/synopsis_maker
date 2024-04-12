<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412075229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE episode_place (episode_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_4F17A599362B62A0 (episode_id), INDEX IDX_4F17A599DA6A219 (place_id), PRIMARY KEY(episode_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, role VARCHAR(15000) DEFAULT NULL, description LONGTEXT DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, author_id INT NOT NULL, INDEX IDX_741D53CDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis_place (synopsis_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_1A3F51818E076A45 (synopsis_id), INDEX IDX_1A3F5181DA6A219 (place_id), PRIMARY KEY(synopsis_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE episode_place ADD CONSTRAINT FK_4F17A599362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_place ADD CONSTRAINT FK_4F17A599DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE synopsis_place ADD CONSTRAINT FK_1A3F51818E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_place ADD CONSTRAINT FK_1A3F5181DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode_place DROP FOREIGN KEY FK_4F17A599362B62A0');
        $this->addSql('ALTER TABLE episode_place DROP FOREIGN KEY FK_4F17A599DA6A219');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDF675F31B');
        $this->addSql('ALTER TABLE synopsis_place DROP FOREIGN KEY FK_1A3F51818E076A45');
        $this->addSql('ALTER TABLE synopsis_place DROP FOREIGN KEY FK_1A3F5181DA6A219');
        $this->addSql('DROP TABLE episode_place');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE synopsis_place');
    }
}
