<?php

declare(strict_types=1);

namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618191552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booking_id INTEGER NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , CONSTRAINT FK_BF5476CA3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA3301C60 ON notification (booking_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__booking AS SELECT id, user_id, event_room_id, date_start, date_end, booking_status FROM booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE booking
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, event_room_id INTEGER NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, booking_status VARCHAR(255) NOT NULL, CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E00CEDDED140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO booking (id, user_id, event_room_id, date_start, date_end, booking_status) SELECT id, user_id, event_room_id, date_start, date_end, booking_status FROM __temp__booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__booking
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E00CEDDED140D93C ON booking (event_room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E00CEDDEA76ED395 ON booking (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password, phone_number, email, company, siret FROM user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL, phone_number VARCHAR(15) NOT NULL, email VARCHAR(50) NOT NULL, company VARCHAR(255) DEFAULT NULL, siret VARCHAR(14) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user (id, username, roles, password, phone_number, email, company, siret) SELECT id, username, roles, password, phone_number, email, company, siret FROM __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__messenger_messages AS SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM __temp__messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__booking AS SELECT id, user_id, event_room_id, date_start, date_end, booking_status FROM booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE booking
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, event_room_id INTEGER NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, booking_status VARCHAR(255) NOT NULL, CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E00CEDDED140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO booking (id, user_id, event_room_id, date_start, date_end, booking_status) SELECT id, user_id, event_room_id, date_start, date_end, booking_status FROM __temp__booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__booking
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E00CEDDEA76ED395 ON booking (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E00CEDDED140D93C ON booking (event_room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__messenger_messages AS SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM __temp__messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password, phone_number, email, company, siret FROM "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL, phone_number VARCHAR(15) NOT NULL, email VARCHAR(50) NOT NULL, company VARCHAR(255) DEFAULT NULL, siret VARCHAR(14) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO "user" (id, username, roles, password, phone_number, email, company, siret) SELECT id, username, roles, password, phone_number, email, company, siret FROM __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)
        SQL);
    }
}
