<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011121845 extends AbstractMigration
{
    /**
     * Get a description of the migration.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }


    /**
     * Perform the "up" migration to create the tables.
     *
     * @param Schema $schema schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C727ACA70');
        $this->addSql('DROP INDEX IDX_6A2CA10C727ACA70 ON media');
        $this->addSql('ALTER TABLE media CHANGE parent_id trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CB281BE2E ON media (trick_id)');
    }


    /**
     * Perform the "down" migration to drop the tables.
     *
     * @param Schema $schema schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CB281BE2E');
        $this->addSql('DROP INDEX IDX_6A2CA10CB281BE2E ON media');
        $this->addSql('ALTER TABLE media CHANGE trick_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C727ACA70 FOREIGN KEY (parent_id) REFERENCES trick (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6A2CA10C727ACA70 ON media (parent_id)');
    }
}
