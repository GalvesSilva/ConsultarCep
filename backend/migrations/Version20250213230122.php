<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213230122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE address (cep VARCHAR(8) NOT NULL, logradouro VARCHAR(255) NOT NULL, complemento VARCHAR(255) DEFAULT NULL, bairro VARCHAR(255) NOT NULL, localidade VARCHAR(255) NOT NULL, uf VARCHAR(2) NOT NULL, ibge VARCHAR(255) NOT NULL, gia VARCHAR(255) DEFAULT NULL, ddd VARCHAR(2) NOT NULL, siafi VARCHAR(255) NOT NULL, PRIMARY KEY(cep)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE enderecos');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE enderecos (id INT AUTO_INCREMENT NOT NULL, cep VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, logradouro VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, bairro VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, localidade VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, uf VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, data_consulta DATETIME DEFAULT \'current_timestamp()\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE address');
    }
}
