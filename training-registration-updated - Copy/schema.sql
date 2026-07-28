-- ============================================================
-- LetsCode Training Registration - Database Schema
-- Import this in phpMyAdmin (or `mysql -u root training_regis < schema.sql`)
-- Creates the database and both tables the app needs. Any extra
-- columns used by the email confirmation feature are added
-- automatically at runtime by db.php / admin/includes/functions.php,
-- but the base tables below still need to exist first.
-- ============================================================

CREATE DATABASE IF NOT EXISTS training_regis
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE training_regis;

-- ------------------------------------------------------------
-- Student registrations
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_registrations (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    full_name        VARCHAR(150)     NOT NULL,
    email            VARCHAR(150)     NOT NULL,
    phone            VARCHAR(30)      NOT NULL,
    age              INT              NOT NULL,
    course           VARCHAR(100)     NOT NULL,
    is_verified      TINYINT(1)       NOT NULL DEFAULT 0,
    verify_token     VARCHAR(64)      NULL,
    verified_at      DATETIME         NULL,
    student_username VARCHAR(50)      NULL,
    student_password VARCHAR(255)     NULL,
    created_at       TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_email (email),
    UNIQUE KEY uniq_student_username (student_username)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Admin panel users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    username       VARCHAR(50)   NOT NULL UNIQUE,
    password       VARCHAR(255)  NOT NULL,
    rows_per_page  INT           NOT NULL DEFAULT 10,
    theme_accent   VARCHAR(20)   NOT NULL DEFAULT '#7c3aed',
    created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- No default admin row is inserted here on purpose -- a hardcoded password
-- hash in a SQL file is a bad idea. After importing this schema, create your
-- admin login by visiting admin/create_admin.php once in your browser
-- (it inserts username "admin" / password "admin123" using PHP's own
-- password_hash(), so it's guaranteed to match). Change that password via
-- admin/settings.php afterwards, then delete or lock down create_admin.php.
