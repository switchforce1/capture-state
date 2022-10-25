<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025171318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE source_group_comparison DROP CONSTRAINT fk_a5f361c261a35484');
        $this->addSql('DROP INDEX idx_a5f361c261a35484');
        $this->addSql('ALTER TABLE source_group_comparison ADD source_group_snapshot1_id INT NOT NULL');
        $this->addSql('ALTER TABLE source_group_comparison ADD source_group_snapshot2_id INT NOT NULL');
        $this->addSql('ALTER TABLE source_group_comparison DROP source_group_snapshot_id');
        $this->addSql('ALTER TABLE source_group_comparison ADD CONSTRAINT FK_A5F361C23CD8C9FE FOREIGN KEY (source_group_snapshot1_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_group_comparison ADD CONSTRAINT FK_A5F361C22E6D6610 FOREIGN KEY (source_group_snapshot2_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A5F361C23CD8C9FE ON source_group_comparison (source_group_snapshot1_id)');
        $this->addSql('CREATE INDEX IDX_A5F361C22E6D6610 ON source_group_comparison (source_group_snapshot2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE source_group_comparison DROP CONSTRAINT FK_A5F361C23CD8C9FE');
        $this->addSql('ALTER TABLE source_group_comparison DROP CONSTRAINT FK_A5F361C22E6D6610');
        $this->addSql('DROP INDEX IDX_A5F361C23CD8C9FE');
        $this->addSql('DROP INDEX IDX_A5F361C22E6D6610');
        $this->addSql('ALTER TABLE source_group_comparison ADD source_group_snapshot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE source_group_comparison DROP source_group_snapshot1_id');
        $this->addSql('ALTER TABLE source_group_comparison DROP source_group_snapshot2_id');
        $this->addSql('ALTER TABLE source_group_comparison ADD CONSTRAINT fk_a5f361c261a35484 FOREIGN KEY (source_group_snapshot_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a5f361c261a35484 ON source_group_comparison (source_group_snapshot_id)');
    }
}
