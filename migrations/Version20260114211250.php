<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260114215000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes email et password à la table player';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE player ADD email VARCHAR(180) NOT NULL, ADD password VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE player DROP COLUMN email, DROP COLUMN password');
    }
}
