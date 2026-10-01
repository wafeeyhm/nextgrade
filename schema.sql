-- NextGrade Complete Database Schema v2
-- Compatible with MySQL 5.7+ / MariaDB / MySQL 8.0+
--
-- [WEB HOSTING / CPANEL NOTE]:
-- When importing via phpMyAdmin into an existing hosting database (e.g. your_cpanel_db),
-- you can skip the 'CREATE DATABASE' and 'USE' statements below.
-- Simply click on your database in phpMyAdmin, then click "Import".
--
-- [COMPLETE DATABASE WITH 1,549+ QUESTIONS]:
-- To import the complete database with all questions, subjects, topics, and revisions,
-- use the all-in-one file: `nextgrade_complete.sql`.

CREATE DATABASE IF NOT EXISTS `nextgrade_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nextgrade_db`;

-- 1. System Admins Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Parents Table (Multi-Parent Support)
CREATE TABLE IF NOT EXISTS `parents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `parent_code` VARCHAR(30) UNIQUE NOT NULL,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `phone` VARCHAR(30) DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Students Table (Kids Linked to Parents, Simple Access)
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `parent_id` INT DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) UNIQUE DEFAULT NULL,
  `pin_code` VARCHAR(20) DEFAULT '1234',
  `avatar` VARCHAR(50) DEFAULT 'star_kid',
  `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)',
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `last_active` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_student_name` (`name`),
  INDEX `idx_student_parent` (`parent_id`),
  CONSTRAINT `fk_student_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Subjects Table
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` VARCHAR(50) PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `title_native` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `icon` VARCHAR(50) NOT NULL,
  `theme_gradient` VARCHAR(100) NOT NULL,
  `accent_color` VARCHAR(50) NOT NULL,
  `sort_order` INT DEFAULT 0,
  `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Topics Table
CREATE TABLE IF NOT EXISTS `topics` (
  `id` VARCHAR(50) PRIMARY KEY,
  `subject_id` VARCHAR(50) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `name_native` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `icon` VARCHAR(50) NOT NULL,
  `color_badge` VARCHAR(50) NOT NULL,
  `revision_time_limit` INT DEFAULT 300, -- 5 minutes in seconds
  `sort_order` INT DEFAULT 0,
  `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)',
  INDEX `idx_topic_subject` (`subject_id`),
  CONSTRAINT `fk_topic_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Revisions Table (5-minute interactive revision content)
CREATE TABLE IF NOT EXISTS `revisions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `topic_id` VARCHAR(50) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `summary` TEXT,
  `content_json` LONGTEXT NOT NULL,
  INDEX `idx_revision_topic` (`topic_id`),
  CONSTRAINT `fk_revision_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Questions Table
CREATE TABLE IF NOT EXISTS `questions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `topic_id` VARCHAR(50) NOT NULL,
  `subject_id` VARCHAR(50) NOT NULL,
  `question_text` TEXT NOT NULL,
  `question_audio` TEXT NOT NULL,
  `lang` VARCHAR(10) DEFAULT 'en', -- 'ms' for Malay, 'en' for English/Maths/Science/ICT
  `question_type` VARCHAR(50) NOT NULL, -- 'multiple_choice', 'syllable_split', 'ordering', 'clock_analog', 'comprehension', 'fill_blank'
  `image_url` VARCHAR(255) DEFAULT NULL,
  `passage` TEXT DEFAULT NULL,
  `options_json` LONGTEXT NOT NULL,
  `correct_answer` TEXT NOT NULL,
  `hint_text` TEXT NOT NULL,
  `hint_audio` TEXT NOT NULL,
  `meta_data_json` LONGTEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `grade_level` VARCHAR(50) DEFAULT 'Kindergarten 3 (KG3)',
  INDEX `idx_q_topic` (`topic_id`),
  INDEX `idx_q_subject` (`subject_id`),
  CONSTRAINT `fk_q_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_q_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Quiz Sessions (Keeps score, progress, time spent)
CREATE TABLE IF NOT EXISTS `quiz_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT DEFAULT NULL,
  `student_name` VARCHAR(100) NOT NULL,
  `subject_id` VARCHAR(50) DEFAULT NULL,
  `topic_id` VARCHAR(50) DEFAULT NULL,
  `total_questions` INT DEFAULT 10,
  `score` INT DEFAULT 0,
  `percentage` DECIMAL(5,2) DEFAULT 0.00,
  `time_spent_seconds` INT DEFAULT 0,
  `completed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_session_student` (`student_id`),
  INDEX `idx_session_date` (`completed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Quiz Session Answers (Detailed insights per question)
CREATE TABLE IF NOT EXISTS `quiz_session_answers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `session_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `student_answer` TEXT,
  `is_correct` TINYINT(1) DEFAULT 0,
  `used_hint` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_qsa_session` (`session_id`),
  CONSTRAINT `fk_qsa_session` FOREIGN KEY (`session_id`) REFERENCES `quiz_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Revision Sessions Table (Track Revision Content Read)
CREATE TABLE IF NOT EXISTS `revision_sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT NOT NULL,
  `student_name` VARCHAR(100) NOT NULL,
  `topic_id` VARCHAR(50) NOT NULL,
  `subject_id` VARCHAR(50) NOT NULL,
  `time_spent_seconds` INT DEFAULT 0,
  `completed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_rev_student` (`student_id`),
  INDEX `idx_rev_topic` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
