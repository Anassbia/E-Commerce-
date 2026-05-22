<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260522011000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Synchronize SQLite schema with Doctrine mapping and messenger transport table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (queue_name, available_at, delivered_at, id)');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, category_id, name, slug, description, sku, price, image_filename, in_stock FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(160) NOT NULL, slug VARCHAR(180) NOT NULL, description CLOB NOT NULL, sku VARCHAR(50) NOT NULL, price NUMERIC(10, 2) NOT NULL, image_filename VARCHAR(120) DEFAULT NULL, in_stock BOOLEAN NOT NULL, CONSTRAINT FK_D34A04ADF12469DE FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, category_id, name, slug, description, sku, price, image_filename, in_stock) SELECT id, category_id, name, slug, description, sku, price, image_filename, in_stock FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD989D9B62 ON product (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADF9038C4 ON product (sku)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE messenger_messages');

        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, category_id, name, slug, description, sku, price, image_filename, in_stock FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(160) NOT NULL, slug VARCHAR(180) NOT NULL, description CLOB NOT NULL, sku VARCHAR(50) NOT NULL, price NUMERIC(10, 2) NOT NULL, image_filename VARCHAR(120) DEFAULT NULL, in_stock BOOLEAN NOT NULL, CONSTRAINT FK_D34A04ADF12469DE FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, category_id, name, slug, description, sku, price, image_filename, in_stock) SELECT id, category_id, name, slug, description, sku, price, image_filename, in_stock FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04A989D9B62 ON product (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04A15F1BFE3 ON product (sku)');
        $this->addSql('CREATE INDEX IDX_D34A04ADF12469DE ON product (category_id)');
    }
}
