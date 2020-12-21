<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215165050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD mess_recrut_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F73CEAEA3 FOREIGN KEY (mess_recrut_id) REFERENCES recruter (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F73CEAEA3 ON message (mess_recrut_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F73CEAEA3');
        $this->addSql('DROP INDEX IDX_B6BD307F73CEAEA3 ON message');
        $this->addSql('ALTER TABLE message DROP mess_recrut_id');
    }
}