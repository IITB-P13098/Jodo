CREATE DATABASE IF NOT EXISTS ci_jodo;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
  user_id           bigint unsigned                     NOT NULL,
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
-- Table structure for table services_cache
--

CREATE TABLE IF NOT EXISTS users_cache (
  cache_id          bigint unsigned                     NOT NULL AUTO_INCREMENT,
  user_id           bigint unsigned                     NOT NULL,
  response          mediumtext        COLLATE utf8_bin  NOT NULL,
  created           timestamp                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cache_id),
  INDEX(user_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table story
--

CREATE TABLE IF NOT EXISTS story (
  story_id          bigint unsigned                     NOT NULL AUTO_INCREMENT,
  title             varchar(128)      COLLATE utf8_bin  NOT NULL UNIQUE,
  created           timestamp                           NOT NULL DEFAULT '0000-00-00 00:00:00',
  modified          timestamp         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (story_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table page
--

CREATE TABLE IF NOT EXISTS page (
  page_id           bigint unsigned                     NOT NULL AUTO_INCREMENT,
  user_id           bigint unsigned                     NOT NULL DEFAULT '0',
  description       varchar(1024)     COLLATE utf8_bin  NOT NULL,
  image_id          varchar(1024)     COLLATE utf8_bin  NOT NULL,
  parent_page_id    bigint unsigned                     DEFAULT NULL,
  story_id          bigint unsigned                     NOT NULL,
  created           timestamp                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (page_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (parent_page_id) REFERENCES page(page_id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (story_id) REFERENCES story(story_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;