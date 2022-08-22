<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818215430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE snapshot ADD source_id INT NOT NULL');
        $this->addSql('ALTER TABLE snapshot ADD CONSTRAINT FK_2C4D1535953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2C4D1535953C1C61 ON snapshot (source_id)');
        $this->addSql('ALTER TABLE source ADD method VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE snapshot DROP CONSTRAINT FK_2C4D1535953C1C61');
        $this->addSql('DROP INDEX IDX_2C4D1535953C1C61');
        $this->addSql('ALTER TABLE snapshot DROP source_id');
        $this->addSql('ALTER TABLE source DROP method');
    }
}
