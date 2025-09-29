<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250926104407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_images (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, sort_order INTEGER NOT NULL, is_primary BOOLEAN NOT NULL, created_at DATETIME NOT NULL, project_id INTEGER NOT NULL, CONSTRAINT FK_F7BB5520166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F7BB5520166D1F9C ON project_images (project_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, title, description, image, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, image VARCHAR(255) DEFAULT NULL, technologies CLOB NOT NULL, github_url VARCHAR(255) DEFAULT NULL, demo_url VARCHAR(255) DEFAULT NULL, featured BOOLEAN NOT NULL, published BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, sort_order INTEGER NOT NULL)');
        $this->addSql('INSERT INTO projects (id, title, description, image, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order) SELECT id, title, description, image, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users AS SELECT id, email, roles, password FROM users');
        $this->addSql('DROP TABLE users');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO users (id, email, roles, password) SELECT id, email, roles, password FROM __temp__users');
        $this->addSql('DROP TABLE __temp__users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_images');
        $this->addSql('CREATE TEMPORARY TABLE __temp__projects AS SELECT id, title, description, image, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order FROM projects');
        $this->addSql('DROP TABLE projects');
        $this->addSql('CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL, image VARCHAR(255) DEFAULT NULL, technologies CLOB NOT NULL --(DC2Type:json)
        , github_url VARCHAR(255) DEFAULT NULL, demo_url VARCHAR(255) DEFAULT NULL, featured BOOLEAN NOT NULL, published BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, sort_order INTEGER NOT NULL)');
        $this->addSql('INSERT INTO projects (id, title, description, image, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order) SELECT id, title, description, image, technologies, github_url, demo_url, featured, published, created_at, updated_at, sort_order FROM __temp__projects');
        $this->addSql('DROP TABLE __temp__projects');
        $this->addSql('CREATE TEMPORARY TABLE __temp__users AS SELECT id, email, roles, password FROM users');
        $this->addSql('DROP TABLE users');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO users (id, email, roles, password) SELECT id, email, roles, password FROM __temp__users');
        $this->addSql('DROP TABLE __temp__users');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
    }
}
