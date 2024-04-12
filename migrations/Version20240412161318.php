<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412161318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(2500) DEFAULT NULL, appearance LONGTEXT DEFAULT NULL, biography LONGTEXT DEFAULT NULL, personality JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE episode_character (episode_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_2DB8260D362B62A0 (episode_id), INDEX IDX_2DB8260D1136BE75 (character_id), PRIMARY KEY(episode_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE synopsis_character (synopsis_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_4C8489A08E076A45 (synopsis_id), INDEX IDX_4C8489A01136BE75 (character_id), PRIMARY KEY(synopsis_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D1136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_character ADD CONSTRAINT FK_4C8489A08E076A45 FOREIGN KEY (synopsis_id) REFERENCES synopsis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE synopsis_character ADD CONSTRAINT FK_4C8489A01136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode_character DROP FOREIGN KEY FK_2DB8260D362B62A0');
        $this->addSql('ALTER TABLE episode_character DROP FOREIGN KEY FK_2DB8260D1136BE75');
        $this->addSql('ALTER TABLE synopsis_character DROP FOREIGN KEY FK_4C8489A08E076A45');
        $this->addSql('ALTER TABLE synopsis_character DROP FOREIGN KEY FK_4C8489A01136BE75');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE episode_character');
        $this->addSql('DROP TABLE synopsis_character');
    }
}
