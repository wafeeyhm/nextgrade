-- NextGrade Complete Database Schema
-- Compatible with MySQL 5.7+ / MariaDB / MySQL 8.0+

CREATE DATABASE IF NOT EXISTS `nextgrade_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nextgrade_db`;

-- 1. Students Table
CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `avatar` VARCHAR(50) DEFAULT 'star_kid',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `last_active` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_student_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Subjects Table
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` VARCHAR(50) PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `title_native` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `icon` VARCHAR(50) NOT NULL,
  `theme_gradient` VARCHAR(100) NOT NULL,
  `accent_color` VARCHAR(50) NOT NULL,
  `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Topics Table
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
  INDEX `idx_topic_subject` (`subject_id`),
  CONSTRAINT `fk_topic_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Revisions Table (5-minute interactive revision content)
CREATE TABLE IF NOT EXISTS `revisions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `topic_id` VARCHAR(50) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `summary` TEXT,
  `content_json` LONGTEXT NOT NULL,
  INDEX `idx_revision_topic` (`topic_id`),
  CONSTRAINT `fk_revision_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Questions Table
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
  INDEX `idx_q_topic` (`topic_id`),
  INDEX `idx_q_subject` (`subject_id`),
  CONSTRAINT `fk_q_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_q_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Quiz Sessions (Keeps score, progress, time spent)
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

-- 7. Quiz Session Answers (Detailed insights per question)
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
