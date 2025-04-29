<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423133224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, tenant_id INT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, is_confidential TINYINT(1) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', deleted_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_8BF21CDE9033212A (tenant_id), INDEX IDX_8BF21CDEB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE9033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE9033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE property
        SQL);
    }
}
