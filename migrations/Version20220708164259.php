<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220708164259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interview CHANGE note note DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE theme CHANGE satisfaction satisfaction DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interview CHANGE note note DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE theme CHANGE satisfaction satisfaction DOUBLE PRECISION NOT NULL');
    }
}
