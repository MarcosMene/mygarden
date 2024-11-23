<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122220443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail CHANGE product_price product_price NUMERIC(7, 2) NOT NULL, CHANGE product_tva product_tva NUMERIC(7, 2) NOT NULL, CHANGE product_promotion product_promotion NUMERIC(7, 2) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE tva tva NUMERIC(7, 2) NOT NULL, CHANGE promotion promotion NUMERIC(7, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail CHANGE product_price product_price DOUBLE PRECISION NOT NULL, CHANGE product_tva product_tva DOUBLE PRECISION NOT NULL, CHANGE product_promotion product_promotion DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE tva tva DOUBLE PRECISION NOT NULL, CHANGE promotion promotion DOUBLE PRECISION NOT NULL');
    }
}
