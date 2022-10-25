<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025152726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE source_group_comparaison_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE source_group_comparison_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE source_group_comparison (id INT NOT NULL, source_group_snapshot_id INT NOT NULL, reason VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A5F361C261A35484 ON source_group_comparison (source_group_snapshot_id)');
        $this->addSql('ALTER TABLE source_group_comparison ADD CONSTRAINT FK_A5F361C261A35484 FOREIGN KEY (source_group_snapshot_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE source_group_comparaison');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE source_group_comparison_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE source_group_comparaison_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE source_group_comparaison (id INT NOT NULL, source_group_snapshot_id INT NOT NULL, reason VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e34644061a35484 ON source_group_comparaison (source_group_snapshot_id)');
        $this->addSql('ALTER TABLE source_group_comparaison ADD CONSTRAINT fk_e34644061a35484 FOREIGN KEY (source_group_snapshot_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE source_group_comparison');
    }
}
