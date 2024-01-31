<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131114429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD is_deleted TINYINT(1) NOT NULL, ADD is_done TINYINT(1) NOT NULL, DROP deleted, DROP done');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task ADD deleted TINYINT(1) NOT NULL, ADD done TINYINT(1) NOT NULL, DROP is_deleted, DROP is_done');
    }
}
