<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200926193342 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(500) NOT NULL, author VARCHAR(500) NOT NULL, price INT NOT NULL, published_date BIGINT NOT NULL, title VARCHAR(1200) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_books (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, book_id_id INT NOT NULL, INDEX IDX_A8D9D1CA9D86650F (user_id_id), INDEX IDX_A8D9D1CA71868B2E (book_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(800) NOT NULL, age INT NOT NULL, address VARCHAR(1000) DEFAULT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_books ADD CONSTRAINT FK_A8D9D1CA9D86650F FOREIGN KEY (user_id_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_books ADD CONSTRAINT FK_A8D9D1CA71868B2E FOREIGN KEY (book_id_id) REFERENCES books (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_books DROP FOREIGN KEY FK_A8D9D1CA71868B2E');
        $this->addSql('ALTER TABLE user_books DROP FOREIGN KEY FK_A8D9D1CA9D86650F');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE user_books');
        $this->addSql('DROP TABLE users');
    }
}
