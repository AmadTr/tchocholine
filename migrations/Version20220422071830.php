<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220422071830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_blog (id INT AUTO_INCREMENT NOT NULL, category_blog_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_7057D6421D383EE9 (category_blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_blog (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_blog (id INT AUTO_INCREMENT NOT NULL, article_blog_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_322FBBDD37323A20 (article_blog_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_blog ADD CONSTRAINT FK_7057D6421D383EE9 FOREIGN KEY (category_blog_id) REFERENCES category_blog (id)');
        $this->addSql('ALTER TABLE image_blog ADD CONSTRAINT FK_322FBBDD37323A20 FOREIGN KEY (article_blog_id) REFERENCES article_blog (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_blog DROP FOREIGN KEY FK_322FBBDD37323A20');
        $this->addSql('ALTER TABLE article_blog DROP FOREIGN KEY FK_7057D6421D383EE9');
        $this->addSql('DROP TABLE article_blog');
        $this->addSql('DROP TABLE category_blog');
        $this->addSql('DROP TABLE image_blog');
    }
}
