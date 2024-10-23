<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022110717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE header ADD category_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header ADD CONSTRAINT FK_6E72A8C1639A3624 FOREIGN KEY (category_product_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_6E72A8C1639A3624 ON header (category_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE header DROP FOREIGN KEY FK_6E72A8C1639A3624');
        $this->addSql('DROP INDEX IDX_6E72A8C1639A3624 ON header');
        $this->addSql('ALTER TABLE header DROP category_product_id');
    }
}
