-- Progress tracking tables

-- First create the achievements table since it's referenced by user_achievements
CREATE TABLE IF NOT EXISTS `achievements` (
  `id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `criteria` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Then create the course_progress table
CREATE TABLE IF NOT EXISTS `course_progress` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `progress` int(11) NOT NULL DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `playlist_id` (`playlist_id`),
  CONSTRAINT `fk_course_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_course_progress_playlist` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create quiz_scores table
CREATE TABLE IF NOT EXISTS `quiz_scores` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `playlist_id` (`playlist_id`),
  CONSTRAINT `fk_quiz_scores_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_quiz_scores_playlist` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create learning_time table
CREATE TABLE IF NOT EXISTS `learning_time` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `minutes_spent` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `playlist_id` (`playlist_id`),
  CONSTRAINT `fk_learning_time_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_learning_time_playlist` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Finally create user_achievements table
CREATE TABLE IF NOT EXISTS `user_achievements` (
  `id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `achievement_id` varchar(20) NOT NULL,
  `date_earned` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `achievement_id` (`achievement_id`),
  CONSTRAINT `fk_user_achievements_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_achievements_achievement` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 