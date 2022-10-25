<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025152047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE source_group_comparison_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE source_group_snapshot_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE source_group_comparison (id INT NOT NULL, source_group_snapshot_id INT NOT NULL, reason VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E34644061A35484 ON source_group_comparison (source_group_snapshot_id)');
        $this->addSql('CREATE TABLE source_group_snapshot (id INT NOT NULL, source_group_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_860435EFBD74EF95 ON source_group_snapshot (source_group_id)');
        $this->addSql('ALTER TABLE source_group_comparison ADD CONSTRAINT FK_E34644061A35484 FOREIGN KEY (source_group_snapshot_id) REFERENCES source_group_snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_group_snapshot ADD CONSTRAINT FK_860435EFBD74EF95 FOREIGN KEY (source_group_id) REFERENCES source_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE source_group_comparison DROP CONSTRAINT FK_E34644061A35484');
        $this->addSql('DROP SEQUENCE source_group_comparison_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE source_group_snapshot_id_seq CASCADE');
        $this->addSql('DROP TABLE source_group_comparison');
        $this->addSql('DROP TABLE source_group_snapshot');
    }
}
