<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423150345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE audit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, resource_type VARCHAR(255) NOT NULL, resource_id VARCHAR(255) NOT NULL, action VARCHAR(255) NOT NULL, context JSON DEFAULT NULL COMMENT '(DC2Type:json)', registered_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_9218FF79A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audit ADD CONSTRAINT FK_9218FF79A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE audit DROP FOREIGN KEY FK_9218FF79A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE audit
        SQL);
    }
}
