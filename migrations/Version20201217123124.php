<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217123124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_expediteur_id INT DEFAULT NULL, recruter_expediteur_id INT DEFAULT NULL, user_destinataire_id INT DEFAULT NULL, recruter_destinataire_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, INDEX IDX_B6BD307FFEBDF231 (user_expediteur_id), INDEX IDX_B6BD307FB6B6370E (recruter_expediteur_id), INDEX IDX_B6BD307FB62423E1 (user_destinataire_id), INDEX IDX_B6BD307F5F477E9 (recruter_destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FFEBDF231 FOREIGN KEY (user_expediteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB6B6370E FOREIGN KEY (recruter_expediteur_id) REFERENCES recruter (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB62423E1 FOREIGN KEY (user_destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F5F477E9 FOREIGN KEY (recruter_destinataire_id) REFERENCES recruter (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE message');
    }
}
