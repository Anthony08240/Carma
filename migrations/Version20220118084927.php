<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220118084927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE point ADD id_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F32479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B7A5F32479F37AE5 ON point (id_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F32479F37AE5');
        $this->addSql('DROP INDEX IDX_B7A5F32479F37AE5 ON point');
        $this->addSql('ALTER TABLE point DROP id_user_id');
    }
}
