<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250624145027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE booking ADD COLUMN reminder_notification_sent_at DATE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__notification AS SELECT id, booking_id, user_id, title, message, created_at, is_read FROM notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booking_id INTEGER NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , is_read BOOLEAN NOT NULL, CONSTRAINT FK_BF5476CA3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO notification (id, booking_id, user_id, title, message, created_at, is_read) SELECT id, booking_id, user_id, title, message, created_at, is_read FROM __temp__notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__notification
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA3301C60 ON notification (booking_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__booking AS SELECT id, user_id, event_room_id, date_start, date_end, booking_status, reminder_notification_created FROM booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE booking
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, event_room_id INTEGER NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, booking_status VARCHAR(255) NOT NULL, reminder_notification_created BOOLEAN DEFAULT 0 NOT NULL, CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E00CEDDED140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO booking (id, user_id, event_room_id, date_start, date_end, booking_status, reminder_notification_created) SELECT id, user_id, event_room_id, date_start, date_end, booking_status, reminder_notification_created FROM __temp__booking
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
            CREATE TEMPORARY TABLE __temp__notification AS SELECT id, booking_id, user_id, title, message, created_at, is_read FROM notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, booking_id INTEGER NOT NULL, user_id INTEGER NOT NULL, title VARCHAR(100) NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , is_read BOOLEAN NOT NULL, CONSTRAINT FK_BF5476CA3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO notification (id, booking_id, user_id, title, message, created_at, is_read) SELECT id, booking_id, user_id, title, message, created_at, is_read FROM __temp__notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__notification
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CA3301C60 ON notification (booking_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF5476CAA76ED395 ON notification (user_id)
        SQL);
    }
}
