<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251221213253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78C1538FD4');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78C1538FD4 FOREIGN KEY (church_id) REFERENCES church (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78C1538FD4');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78C1538FD4 FOREIGN KEY (church_id) REFERENCES church (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
