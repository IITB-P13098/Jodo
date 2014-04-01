-- CREATE DATABASE IF NOT EXISTS ci_jodo;

-- --------------------------------------------------------

--
-- Table structure for table ci_sessions
--

CREATE TABLE IF NOT EXISTS ci_sessions (
  session_id        varchar(40)       COLLATE utf8_bin  NOT NULL DEFAULT '0',
  ip_address        varchar(45)       COLLATE utf8_bin  NOT NULL DEFAULT '0',
  user_agent        varchar(120)      COLLATE utf8_bin  NOT NULL,
  last_activity     int unsigned                        NOT NULL DEFAULT '0',
  user_data         text              COLLATE utf8_bin  NOT NULL,
  PRIMARY KEY (session_id),
  KEY last_activity_idx (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table users
--

CREATE TABLE IF NOT EXISTS users (
  user_id           bigint unsigned                     NOT NULL AUTO_INCREMENT,
  password          varchar(255)      COLLATE utf8_bin  NOT NULL,
  email             varchar(100)      COLLATE utf8_bin  NOT NULL UNIQUE,
  email_verified    tinyint(1)                          NOT NULL DEFAULT '0',
  email_key         varchar(50)       COLLATE utf8_bin  DEFAULT NULL,
  last_ip           varchar(40)       COLLATE utf8_bin  NOT NULL,
  last_login        datetime                            NOT NULL DEFAULT '0000-00-00 00:00:00',
  created           timestamp                           NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified          timestamp         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table user_autologin
--

CREATE TABLE IF NOT EXISTS user_autologin (
  key_id            char(32)          COLLATE utf8_bin  NOT NULL,
  user_id           bigint unsigned                     NOT NULL DEFAULT '0',
  user_agent        varchar(150)      COLLATE utf8_bin  NOT NULL,
  last_ip           varchar(40)       COLLATE utf8_bin  NOT NULL,
  last_login        timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (key_id, user_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table user_profiles
--

CREATE TABLE IF NOT EXISTS user_profiles (
  user_id           bigint unsigned                     NOT NULL,
  disp_name         varchar(32)       COLLATE utf8_bin  NOT NULL,
  bio               text              COLLATE utf8_bin  DEFAULT NULL,
  profile_image_id  bigint unsigned                     DEFAULT NULL,
  cover_image_id    bigint unsigned                     DEFAULT NULL,
  PRIMARY KEY (user_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table story
--

CREATE TABLE IF NOT EXISTS story (
  story_id          bigint unsigned                     NOT NULL AUTO_INCREMENT,
  user_id           bigint unsigned                     NOT NULL DEFAULT '0',
  caption           text              COLLATE utf8_bin  DEFAULT NULL,
  parent_story_id   bigint unsigned                     DEFAULT NULL,
  start_story_id    bigint unsigned                     DEFAULT NULL,
  created           timestamp                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (story_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (parent_story_id) REFERENCES story(story_id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (start_story_id) REFERENCES story(story_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table story
--

CREATE TABLE IF NOT EXISTS story_title (
  title_id          bigint unsigned                     NOT NULL AUTO_INCREMENT,
  story_id          bigint unsigned                     NOT NULL,
  title             varchar(1024)     COLLATE utf8_bin  NOT NULL,
  PRIMARY KEY (title_id),
  FOREIGN KEY (story_id) REFERENCES story(story_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for image
--

CREATE TABLE IF NOT EXISTS images (
  image_id          bigint unsigned                     NOT NULL AUTO_INCREMENT,
  story_id          bigint unsigned                     NOT NULL,
  file_name         varchar(1024)     COLLATE utf8_bin  NOT NULL,
  raw_name          varchar(1024)     COLLATE utf8_bin  NOT NULL,
  orig_name         varchar(1024)     COLLATE utf8_bin  NOT NULL,
  file_size         int unsigned                        NOT NULL,
  image_width       int unsigned                        NOT NULL,
  image_height      int unsigned                        NOT NULL,
  image_type        varchar(64)       COLLATE utf8_bin  NOT NULL,
  PRIMARY KEY (image_id),
  FOREIGN KEY (story_id) REFERENCES story(story_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
