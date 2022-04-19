<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419190232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "notification_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "notification" (id INT NOT NULL, message_id INT DEFAULT NULL, chat_user_id INT DEFAULT NULL, read BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CA537A1329 ON "notification" (message_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CAEB601FFC ON "notification" (chat_user_id)');
        $this->addSql('ALTER TABLE "notification" ADD CONSTRAINT FK_BF5476CA537A1329 FOREIGN KEY (message_id) REFERENCES "message" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "notification" ADD CONSTRAINT FK_BF5476CAEB601FFC FOREIGN KEY (chat_user_id) REFERENCES "chat_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "notification_id_seq" CASCADE');
        $this->addSql('DROP TABLE "notification"');
    }
}
