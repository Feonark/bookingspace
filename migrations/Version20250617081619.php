<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617081619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE "app_user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL, phone_number VARCHAR(15) NOT NULL, email VARCHAR(50) NOT NULL, company VARCHAR(255) DEFAULT NULL, siret VARCHAR(14) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "app_user" (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE booking (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, app_user_id INTEGER NOT NULL, event_room_id INTEGER NOT NULL, date_start DATE NOT NULL, date_end DATE NOT NULL, booking_status VARCHAR(255) NOT NULL, CONSTRAINT FK_E00CEDDE4A3353D8 FOREIGN KEY (app_user_id) REFERENCES "app_user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E00CEDDED140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E00CEDDE4A3353D8 ON booking (app_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E00CEDDED140D93C ON booking (event_room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE equipment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ergonomic_criteria (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, capacity INTEGER NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_room_software (event_room_id INTEGER NOT NULL, software_id INTEGER NOT NULL, PRIMARY KEY(event_room_id, software_id), CONSTRAINT FK_F36F18A7D140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F36F18A7D7452741 FOREIGN KEY (software_id) REFERENCES software (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F36F18A7D140D93C ON event_room_software (event_room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F36F18A7D7452741 ON event_room_software (software_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_room_equipment (event_room_id INTEGER NOT NULL, equipment_id INTEGER NOT NULL, PRIMARY KEY(event_room_id, equipment_id), CONSTRAINT FK_90D58399D140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_90D58399517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_90D58399D140D93C ON event_room_equipment (event_room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_90D58399517FE9FE ON event_room_equipment (equipment_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_room_ergonomic_criteria (event_room_id INTEGER NOT NULL, ergonomic_criteria_id INTEGER NOT NULL, PRIMARY KEY(event_room_id, ergonomic_criteria_id), CONSTRAINT FK_F01723DCD140D93C FOREIGN KEY (event_room_id) REFERENCES event_room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F01723DC8C8F50CB FOREIGN KEY (ergonomic_criteria_id) REFERENCES ergonomic_criteria (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F01723DCD140D93C ON event_room_ergonomic_criteria (event_room_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F01723DC8C8F50CB ON event_room_ergonomic_criteria (ergonomic_criteria_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE software (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
            )
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE "app_user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE booking
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE equipment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ergonomic_criteria
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_room
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_room_software
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_room_equipment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_room_ergonomic_criteria
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE software
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
