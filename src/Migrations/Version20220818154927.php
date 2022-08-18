<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818154927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comparison_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE snapshot_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE source_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE source_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comparison (id INT NOT NULL, source_id INT NOT NULL, snapshot1_id INT NOT NULL, snapshot2_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reason VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E54AC6D6953C1C61 ON comparison (source_id)');
        $this->addSql('CREATE INDEX IDX_E54AC6D65AC46859 ON comparison (snapshot1_id)');
        $this->addSql('CREATE INDEX IDX_E54AC6D64871C7B7 ON comparison (snapshot2_id)');
        $this->addSql('COMMENT ON COLUMN comparison.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN comparison.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE snapshot (id INT NOT NULL, uuid VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data JSON DEFAULT NULL, raw_data TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN snapshot.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN snapshot.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE source (id INT NOT NULL, source_group_id INT NOT NULL, label VARCHAR(255) NOT NULL, type_code VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5F8A7F73BD74EF95 ON source (source_group_id)');
        $this->addSql('CREATE TABLE source_tag (source_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(source_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_527DB2C2953C1C61 ON source_tag (source_id)');
        $this->addSql('CREATE INDEX IDX_527DB2C2BAD26311 ON source_tag (tag_id)');
        $this->addSql('CREATE TABLE source_group (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN source_group.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN source_group.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, label VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE comparison ADD CONSTRAINT FK_E54AC6D6953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comparison ADD CONSTRAINT FK_E54AC6D65AC46859 FOREIGN KEY (snapshot1_id) REFERENCES snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comparison ADD CONSTRAINT FK_E54AC6D64871C7B7 FOREIGN KEY (snapshot2_id) REFERENCES snapshot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73BD74EF95 FOREIGN KEY (source_group_id) REFERENCES source_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_tag ADD CONSTRAINT FK_527DB2C2953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_tag ADD CONSTRAINT FK_527DB2C2BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comparison DROP CONSTRAINT FK_E54AC6D65AC46859');
        $this->addSql('ALTER TABLE comparison DROP CONSTRAINT FK_E54AC6D64871C7B7');
        $this->addSql('ALTER TABLE comparison DROP CONSTRAINT FK_E54AC6D6953C1C61');
        $this->addSql('ALTER TABLE source_tag DROP CONSTRAINT FK_527DB2C2953C1C61');
        $this->addSql('ALTER TABLE source DROP CONSTRAINT FK_5F8A7F73BD74EF95');
        $this->addSql('ALTER TABLE source_tag DROP CONSTRAINT FK_527DB2C2BAD26311');
        $this->addSql('DROP SEQUENCE comparison_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE snapshot_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE source_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE source_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP TABLE comparison');
        $this->addSql('DROP TABLE snapshot');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE source_tag');
        $this->addSql('DROP TABLE source_group');
        $this->addSql('DROP TABLE tag');
    }
}
