<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250926131632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects ADD COLUMN title_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE projects ADD COLUMN description_en CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, title, description, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, technologies CLOB NOT NULL, github_url VARCHAR(255) DEFAULT NULL, demo_url VARCHAR(255) DEFAULT NULL, featured BOOLEAN NOT NULL, published BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, sort_order INTEGER NOT NULL)');
        $this->addSql('INSERT INTO projects (id, title, description, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order) SELECT id, title, description, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
    }
}
