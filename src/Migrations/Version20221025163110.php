<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025163110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE snapshot ADD source_group_snapshot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE snapshot ADD CONSTRAINT FK_2C4D153561A35484 FOREIGN KEY (source_group_snapshot_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2C4D153561A35484 ON snapshot (source_group_snapshot_id)');
        $this->addSql('ALTER TABLE source_group_comparison ALTER source_group_snapshot_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE snapshot DROP CONSTRAINT FK_2C4D153561A35484');
        $this->addSql('DROP INDEX IDX_2C4D153561A35484');
        $this->addSql('ALTER TABLE snapshot DROP source_group_snapshot_id');
        $this->addSql('ALTER TABLE source_group_comparison ALTER source_group_snapshot_id SET NOT NULL');
    }
}
