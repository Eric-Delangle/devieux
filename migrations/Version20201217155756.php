<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217155756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F5F477E9');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB62423E1');
        $this->addSql('DROP INDEX IDX_B6BD307F5F477E9 ON message');
        $this->addSql('DROP INDEX IDX_B6BD307FB62423E1 ON message');
        $this->addSql('ALTER TABLE message ADD destinataire_id INT DEFAULT NULL, DROP user_destinataire_id, DROP recruter_destinataire_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA4F84F6E ON message (destinataire_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA4F84F6E');
        $this->addSql('DROP INDEX IDX_B6BD307FA4F84F6E ON message');
        $this->addSql('ALTER TABLE message ADD recruter_destinataire_id INT DEFAULT NULL, CHANGE destinataire_id user_destinataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F5F477E9 FOREIGN KEY (recruter_destinataire_id) REFERENCES recruter (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB62423E1 FOREIGN KEY (user_destinataire_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F5F477E9 ON message (recruter_destinataire_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FB62423E1 ON message (user_destinataire_id)');
    }
}
