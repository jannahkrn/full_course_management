-- ============================================================
-- Smart Exam – BR-005 Course Management
-- MySQL Schema (raw SQL)
-- Generated for Laravel 10+ / MySQL 8.0+
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- ─────────────────────────────────────────────────────────────
-- 1. USERS
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`              VARCHAR(255)    NOT NULL,
    `email`             VARCHAR(255)    NOT NULL,
    `email_verified_at` TIMESTAMP       NULL DEFAULT NULL,
    `password`          VARCHAR(255)    NOT NULL,
    `role`              ENUM('admin','teacher','student') NOT NULL DEFAULT 'student',
    `avatar`            VARCHAR(255)    NULL DEFAULT NULL,
    `phone`             VARCHAR(20)     NULL DEFAULT NULL,
    `department`        VARCHAR(255)    NULL DEFAULT NULL,
    `is_active`         TINYINT(1)      NOT NULL DEFAULT 1,
    `remember_token`    VARCHAR(100)    NULL DEFAULT NULL,
    `created_at`        TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`        TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_role_index` (`role`),
    KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 2. PERSONAL ACCESS TOKENS (Sanctum)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tokenable_type` VARCHAR(255)    NOT NULL,
    `tokenable_id`   BIGINT UNSIGNED NOT NULL,
    `name`           VARCHAR(255)    NOT NULL,
    `token`          VARCHAR(64)     NOT NULL,
    `abilities`      TEXT            NULL DEFAULT NULL,
    `last_used_at`   TIMESTAMP       NULL DEFAULT NULL,
    `expires_at`     TIMESTAMP       NULL DEFAULT NULL,
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
    KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 3. COURSE CATEGORIES (Kategori Mata Kuliah)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_categories` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255)    NOT NULL,
    `slug`        VARCHAR(255)    NOT NULL,
    `description` TEXT            NULL DEFAULT NULL,
    `is_active`   TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `course_categories_slug_unique` (`slug`),
    KEY `course_categories_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 4. COURSES (Mata Kuliah)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `courses` (
    `id`                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`               VARCHAR(255)    NOT NULL,
    `slug`                VARCHAR(255)    NOT NULL,
    `code`                VARCHAR(50)     NULL DEFAULT NULL,
    `category_id`         BIGINT UNSIGNED NULL DEFAULT NULL,
    `description`         TEXT            NULL DEFAULT NULL,
    `language`            VARCHAR(10)     NOT NULL DEFAULT 'en',
    `department`          VARCHAR(255)    NULL DEFAULT NULL,
    `department_url`      VARCHAR(500)    NULL DEFAULT NULL,
    `thumbnail`           VARCHAR(255)    NULL DEFAULT NULL,
    `video_url`           VARCHAR(500)    NULL DEFAULT NULL,
    `template_course_id`  BIGINT UNSIGNED NULL DEFAULT NULL,

    -- Access & Subscription settings (from UI)
    `access_type`         ENUM('public','private','restricted') NOT NULL DEFAULT 'private',
    `is_registered`       TINYINT(1)      NOT NULL DEFAULT 0  COMMENT 'Terdaftar',
    `is_allowed`          TINYINT(1)      NOT NULL DEFAULT 1  COMMENT 'Tidak Terdaftar Diizinkan',
    `subscription_type`   ENUM('allowed','teacher_only') NOT NULL DEFAULT 'allowed' COMMENT 'Langganan',
    `allow_unsubscribe`   TINYINT(1)      NOT NULL DEFAULT 1  COMMENT 'Berhenti Berlangganan',
    `storage_limit_mb`    INT UNSIGNED    NULL DEFAULT NULL    COMMENT 'Ruang Penyimpanan (MB)',
    `is_special`          TINYINT(1)      NOT NULL DEFAULT 0  COMMENT 'Mata Kuliah Khusus',

    `tags`                JSON            NULL DEFAULT NULL,
    `is_active`           TINYINT(1)      NOT NULL DEFAULT 1,
    `published_at`        TIMESTAMP       NULL DEFAULT NULL,
    `created_at`          TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`          TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`          TIMESTAMP       NULL DEFAULT NULL,

    PRIMARY KEY (`id`),
    UNIQUE KEY `courses_slug_unique` (`slug`),
    UNIQUE KEY `courses_code_unique` (`code`),
    KEY `courses_category_id_foreign` (`category_id`),
    KEY `courses_template_course_id_foreign` (`template_course_id`),
    KEY `courses_is_active_published_index` (`is_active`, `published_at`),
    KEY `courses_language_index` (`language`),

    CONSTRAINT `courses_category_id_foreign`
        FOREIGN KEY (`category_id`) REFERENCES `course_categories` (`id`) ON DELETE SET NULL,
    CONSTRAINT `courses_template_course_id_foreign`
        FOREIGN KEY (`template_course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 5. COURSE_TEACHERS (Pivot: Course ↔ Teacher)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_teachers` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id`   BIGINT UNSIGNED NOT NULL,
    `user_id`     BIGINT UNSIGNED NOT NULL,
    `role`        ENUM('primary','co_teacher') NOT NULL DEFAULT 'co_teacher',
    `assigned_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `course_teachers_course_user_unique` (`course_id`, `user_id`),
    KEY `course_teachers_user_id_foreign` (`user_id`),
    CONSTRAINT `course_teachers_course_id_foreign`
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `course_teachers_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 6. ENROLLMENTS (Pendaftaran Peserta)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `enrollments` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id`    BIGINT UNSIGNED NOT NULL,
    `user_id`      BIGINT UNSIGNED NOT NULL,
    `status`       ENUM('active','inactive','completed','dropped') NOT NULL DEFAULT 'active',
    `enrolled_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `completed_at` TIMESTAMP       NULL DEFAULT NULL,
    `dropped_at`   TIMESTAMP       NULL DEFAULT NULL,
    `created_at`   TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `enrollments_course_user_unique` (`course_id`, `user_id`),
    KEY `enrollments_course_status_index` (`course_id`, `status`),
    KEY `enrollments_user_status_index` (`user_id`, `status`),
    CONSTRAINT `enrollments_course_id_foreign`
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `enrollments_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 7. COURSE_SESSIONS (Daftar Sesi)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_sessions` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id`   BIGINT UNSIGNED NOT NULL,
    `title`       VARCHAR(255)    NOT NULL,
    `description` TEXT            NULL DEFAULT NULL,
    `order`       INT UNSIGNED    NOT NULL DEFAULT 0,
    `is_active`   TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `course_sessions_course_order_index` (`course_id`, `order`),
    CONSTRAINT `course_sessions_course_id_foreign`
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 8. SESSION_CATEGORIES (Kategori Sesi)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `session_categories` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id`   BIGINT UNSIGNED NOT NULL,
    `name`        VARCHAR(255)    NOT NULL,
    `slug`        VARCHAR(255)    NULL DEFAULT NULL,
    `description` TEXT            NULL DEFAULT NULL,
    `is_active`   TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`  TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`  TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `session_categories_course_id_foreign` (`course_id`),
    CONSTRAINT `session_categories_course_id_foreign`
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 9. COURSE_MATERIALS (Materi)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_materials` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_id`   BIGINT UNSIGNED NOT NULL,
    `title`        VARCHAR(255)    NOT NULL,
    `description`  TEXT            NULL DEFAULT NULL,
    `type`         ENUM('document','video','link','text') NOT NULL DEFAULT 'document',
    `file_path`    VARCHAR(500)    NULL DEFAULT NULL,
    `file_url`     VARCHAR(500)    NULL DEFAULT NULL,
    `file_name`    VARCHAR(255)    NULL DEFAULT NULL,
    `file_size_kb` INT UNSIGNED    NULL DEFAULT NULL,
    `order`        INT UNSIGNED    NOT NULL DEFAULT 0,
    `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`   TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`   TIMESTAMP       NULL DEFAULT NULL,
    `deleted_at`   TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `course_materials_session_order_index` (`session_id`, `order`),
    CONSTRAINT `course_materials_session_id_foreign`
        FOREIGN KEY (`session_id`) REFERENCES `course_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 10. COURSE_EXERCISES (Latihan)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_exercises` (
    `id`               BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `session_id`       BIGINT UNSIGNED  NOT NULL,
    `title`            VARCHAR(255)     NOT NULL,
    `description`      TEXT             NULL DEFAULT NULL,
    `type`             ENUM('quiz','assignment','exam') NOT NULL DEFAULT 'assignment',
    `duration_minutes` INT UNSIGNED     NULL DEFAULT NULL,
    `max_score`        DECIMAL(5,2)     NOT NULL DEFAULT 100.00,
    `passing_score`    DECIMAL(5,2)     NOT NULL DEFAULT 60.00,
    `open_at`          TIMESTAMP        NULL DEFAULT NULL,
    `close_at`         TIMESTAMP        NULL DEFAULT NULL,
    `order`            INT UNSIGNED     NOT NULL DEFAULT 0,
    `is_active`        TINYINT(1)       NOT NULL DEFAULT 1,
    `created_at`       TIMESTAMP        NULL DEFAULT NULL,
    `updated_at`       TIMESTAMP        NULL DEFAULT NULL,
    `deleted_at`       TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `course_exercises_session_id_foreign` (`session_id`),
    CONSTRAINT `course_exercises_session_id_foreign`
        FOREIGN KEY (`session_id`) REFERENCES `course_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────────────────────
-- 11. COURSE_ACTIVITY_LOGS (Riwayat / Aktivitas)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `course_activity_logs` (
    `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id`      BIGINT UNSIGNED NOT NULL,
    `user_id`        BIGINT UNSIGNED NOT NULL,
    `action`         VARCHAR(100)    NOT NULL COMMENT 'visited, material_viewed, exercise_submitted, enrolled, etc.',
    `loggable_type`  VARCHAR(255)    NULL DEFAULT NULL,
    `loggable_id`    BIGINT UNSIGNED NULL DEFAULT NULL,
    `meta`           JSON            NULL DEFAULT NULL,
    `accessed_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at`     TIMESTAMP       NULL DEFAULT NULL,
    `updated_at`     TIMESTAMP       NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `activity_logs_user_course_index` (`user_id`, `course_id`),
    KEY `activity_logs_user_accessed_index` (`user_id`, `accessed_at`),
    KEY `activity_logs_loggable_index` (`loggable_type`, `loggable_id`),
    CONSTRAINT `activity_logs_course_id_foreign`
        FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `activity_logs_user_id_foreign`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ─────────────────────────────────────────────────────────────
-- SEED DATA (Demo / Initial Data)
-- ─────────────────────────────────────────────────────────────

-- Categories
INSERT INTO `course_categories` (`name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
('Pemrograman', 'pemrograman', 1, NOW(), NOW()),
('Teori',       'teori',       1, NOW(), NOW()),
('Projek',      'projek',      1, NOW(), NOW());

-- Users (password = 'password' hashed with bcrypt)
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('Super Admin',       'admin@smartexam.id',     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',   1, NOW(), NOW()),
('Albert Mandala, S. Pd',  'albert@smartexam.id',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 1, NOW(), NOW()),
('Nurmala, S. Pd, M.T',    'nurmala@smartexam.id',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 1, NOW(), NOW()),
('Sukha Pandji, S. Pd',    'sukha@smartexam.id',     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 1, NOW(), NOW()),
('Madeleine S',            'madeleine@smartexam.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 1, NOW(), NOW());

-- Courses
INSERT INTO `courses` (`title`, `slug`, `code`, `category_id`, `language`, `access_type`, `is_registered`, `is_allowed`, `allow_unsubscribe`, `is_active`, `published_at`, `created_at`, `updated_at`) VALUES
('AI for Beginners',           'ai-for-beginners',           'AIBEG01',  3, 'en', 'private', 0, 1, 1, 1, NOW(), NOW(), NOW()),
('Web Development',            'web-development',            'WEBDEV01', 1, 'en', 'private', 1, 1, 1, 1, NOW(), NOW(), NOW()),
('Dasar Perancangan Antarmuka','dasar-perancangan-antarmuka','DPA01',    NULL,'id','private', 0, 1, 1, 1, NOW(), NOW(), NOW()),
('Dasar-dasar Pemrograman',    'dasar-dasar-pemrograman',    'DDP01',    1, 'en', 'private', 1, 0, 1, 1, NOW(), NOW(), NOW()),
('Algoritma Pemrograman',      'algoritma-pemrograman',      'ALGPROG01',1, 'en', 'private', 0, 1, 1, 1, NOW(), NOW(), NOW()),
('Pemrograman Berbasis Objek', 'pemrograman-berbasis-objek', 'PBO01',    1, 'en', 'private', 1, 0, 0, 1, NOW(), NOW(), NOW()),
('Matematika Dasar',           'matematika-dasar',           'MATDAS01', 2, 'id', 'private', 0, 1, 1, 1, NOW(), NOW(), NOW()),
('Interaksi Manusia Komputer', 'interaksi-manusia-komputer', 'IMK01',    3, 'id', 'private', 0, 1, 1, 1, NOW(), NOW(), NOW()),
('Search Engine Optimization', 'search-engine-optimization', 'SEO01',    1, 'en', 'private', 1, 1, 1, 1, NOW(), NOW(), NOW());

-- Course Teachers (user_id 2=albert, 3=nurmala, 4=sukha)
INSERT INTO `course_teachers` (`course_id`, `user_id`, `role`, `assigned_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'primary', NOW(), NOW(), NOW()),
(2, 3, 'primary', NOW(), NOW(), NOW()),
(3, 3, 'primary', NOW(), NOW(), NOW()),
(4, 2, 'primary', NOW(), NOW(), NOW()),
(5, 4, 'primary', NOW(), NOW(), NOW()),
(6, 4, 'primary', NOW(), NOW(), NOW()),
(7, 3, 'primary', NOW(), NOW(), NOW()),
(8, 3, 'primary', NOW(), NOW(), NOW()),
(9, 4, 'primary', NOW(), NOW(), NOW());

-- Enroll student (user_id 5 = Madeleine) into all courses
INSERT INTO `enrollments` (`course_id`, `user_id`, `status`, `enrolled_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'active', NOW(), NOW(), NOW()),
(2, 5, 'active', NOW(), NOW(), NOW()),
(3, 5, 'active', NOW(), NOW(), NOW()),
(4, 5, 'active', NOW(), NOW(), NOW()),
(5, 5, 'active', NOW(), NOW(), NOW()),
(6, 5, 'active', NOW(), NOW(), NOW()),
(7, 5, 'active', NOW(), NOW(), NOW()),
(8, 5, 'active', NOW(), NOW(), NOW()),
(9, 5, 'active', NOW(), NOW(), NOW());

-- Sessions per course (1 session each for demo)
INSERT INTO `course_sessions` (`course_id`, `title`, `description`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Sesi 1 - Pengenalan AI',          NULL, 1, 1, NOW(), NOW()),
(2, 'Sesi 1 - Pengenalan Web Dev',     NULL, 1, 1, NOW(), NOW()),
(3, 'Sesi 1 - Dasar Perancangan',      NULL, 1, 1, NOW(), NOW()),
(4, 'Sesi 1 - Konsep Pemrograman',     NULL, 1, 1, NOW(), NOW()),
(5, 'Sesi 1 - Algoritma Dasar',        NULL, 1, 1, NOW(), NOW()),
(6, 'Sesi 1 - Konsep OOP',            NULL, 1, 1, NOW(), NOW()),
(7, 'Sesi 1 - Logika Matematika',      NULL, 1, 1, NOW(), NOW()),
(8, 'Sesi 1 - Prinsip IMK',            NULL, 1, 1, NOW(), NOW()),
(9, 'Sesi 1 - Dasar SEO',             NULL, 1, 1, NOW(), NOW());

-- Materials per session
INSERT INTO `course_materials` (`session_id`, `title`, `type`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Materi Pengenalan AI',       'document', 1, 1, NOW(), NOW()),
(2, 'Materi HTML & CSS Dasar',    'document', 1, 1, NOW(), NOW()),
(3, 'Materi Desain UI/UX',        'document', 1, 1, NOW(), NOW()),
(4, 'Materi Algoritma Dasar',     'document', 1, 1, NOW(), NOW()),
(5, 'Materi Sorting & Searching', 'document', 1, 1, NOW(), NOW()),
(6, 'Materi Konsep OOP',          'document', 1, 1, NOW(), NOW()),
(7, 'Materi Logika Proposisi',    'document', 1, 1, NOW(), NOW()),
(8, 'Materi Prinsip Nielsen',     'document', 1, 1, NOW(), NOW()),
(9, 'Materi On-Page SEO',         'document', 1, 1, NOW(), NOW());

-- Exercises per session
INSERT INTO `course_exercises` (`session_id`, `title`, `type`, `max_score`, `passing_score`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Latihan 1 - AI Basics',     'assignment', 100, 60, 1, 1, NOW(), NOW()),
(2, 'Latihan 1 - HTML Dasar',    'assignment', 100, 60, 1, 1, NOW(), NOW()),
(3, 'Latihan 1 - Wireframing',   'assignment', 100, 60, 1, 1, NOW(), NOW()),
(4, 'Latihan 1 - Pseudocode',    'assignment', 100, 60, 1, 1, NOW(), NOW()),
(5, 'Latihan 1 - Bubble Sort',   'quiz',       100, 60, 1, 1, NOW(), NOW()),
(6, 'Latihan 1 - Class Diagram', 'assignment', 100, 60, 1, 1, NOW(), NOW()),
(7, 'Latihan 1 - Logika',        'quiz',       100, 60, 1, 1, NOW(), NOW()),
(8, 'Latihan 1 - Usability Test','assignment', 100, 60, 1, 1, NOW(), NOW()),
(9, 'Latihan 1 - Keyword Research','assignment',100, 60, 1, 1, NOW(), NOW());