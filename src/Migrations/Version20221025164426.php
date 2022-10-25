<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221025164426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("SELECT setval('comparison_id_seq', (SELECT MAX(id) FROM comparison)+1);");
        $this->addSql("SELECT setval('snapshot_id_seq', (SELECT MAX(id) FROM snapshot)+1);");
        $this->addSql("SELECT setval('source_group_comparison_id_seq', (SELECT MAX(id) FROM source_group_comparison)+1);");
        $this->addSql("SELECT setval('source_group_id_seq', (SELECT MAX(id) FROM source_group)+1);");
        $this->addSql("SELECT setval('source_group_snapshot_id_seq', (SELECT MAX(id) FROM source_group_snapshot)+1);");
        $this->addSql("SELECT setval('source_id_seq', (SELECT MAX(id) FROM source)+1);");
        $this->addSql("SELECT setval('tag_id_seq', (SELECT MAX(id) FROM tag)+1);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
