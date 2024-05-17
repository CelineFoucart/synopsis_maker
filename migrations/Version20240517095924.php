<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517095924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` ADD nationality VARCHAR(255) DEFAULT NULL, ADD job VARCHAR(255) DEFAULT NULL, ADD birthday VARCHAR(255) DEFAULT NULL, ADD birthday_place VARCHAR(255) DEFAULT NULL, ADD death_date VARCHAR(255) DEFAULT NULL, ADD death_place VARCHAR(255) DEFAULT NULL, ADD parents VARCHAR(255) DEFAULT NULL, ADD children VARCHAR(255) DEFAULT NULL, ADD siblings VARCHAR(255) DEFAULT NULL, ADD partner VARCHAR(255) DEFAULT NULL, ADD species VARCHAR(255) DEFAULT NULL, ADD gender VARCHAR(255) DEFAULT NULL, ADD role LONGTEXT DEFAULT NULL, ADD complementary LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD complementary LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP nationality, DROP job, DROP birthday, DROP birthday_place, DROP death_date, DROP death_place, DROP parents, DROP children, DROP siblings, DROP partner, DROP species, DROP gender, DROP role, DROP complementary');
        $this->addSql('ALTER TABLE place DROP complementary');
    }
}
