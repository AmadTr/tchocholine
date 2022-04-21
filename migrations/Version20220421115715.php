<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421115715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_blog ADD category_blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_blog ADD CONSTRAINT FK_7057D6421D383EE9 FOREIGN KEY (category_blog_id) REFERENCES category_blog (id)');
        $this->addSql('CREATE INDEX IDX_7057D6421D383EE9 ON article_blog (category_blog_id)');
        $this->addSql('ALTER TABLE image_blog ADD article_blog_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_blog ADD CONSTRAINT FK_322FBBDD37323A20 FOREIGN KEY (article_blog_id) REFERENCES article_blog (id)');
        $this->addSql('CREATE INDEX IDX_322FBBDD37323A20 ON image_blog (article_blog_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_blog DROP FOREIGN KEY FK_7057D6421D383EE9');
        $this->addSql('DROP INDEX IDX_7057D6421D383EE9 ON article_blog');
        $this->addSql('ALTER TABLE article_blog DROP category_blog_id');
        $this->addSql('ALTER TABLE image_blog DROP FOREIGN KEY FK_322FBBDD37323A20');
        $this->addSql('DROP INDEX IDX_322FBBDD37323A20 ON image_blog');
        $this->addSql('ALTER TABLE image_blog DROP article_blog_id');
    }
}
