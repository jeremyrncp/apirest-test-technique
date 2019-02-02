<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190202124349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE movie (imdb_id VARCHAR(9) NOT NULL, title VARCHAR(255) NOT NULL, poster LONGTEXT NOT NULL, PRIMARY KEY(imdb_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie_choices (user_id INT NOT NULL, movie_id VARCHAR(9) NOT NULL, INDEX IDX_2558D417A76ED395 (user_id), INDEX IDX_2558D4178F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_choices ADD CONSTRAINT FK_2558D417A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE movie_choices ADD CONSTRAINT FK_2558D4178F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (imdb_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE movie_choices DROP FOREIGN KEY FK_2558D4178F93B6FC');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_choices');
    }
}
