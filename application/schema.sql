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
  description       varchar(1024)     COLLATE utf8_bin  NOT NULL,
  image_id          varchar(1024)     COLLATE utf8_bin  NOT NULL,
  parent_page_id    bigint unsigned                     DEFAULT NULL,
  story_id          bigint unsigned                     NOT NULL,
  created           timestamp                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (page_id),
  FOREIGN KEY (parent_page_id) REFERENCES page(page_id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (story_id) REFERENCES story(story_id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;