-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2015 at 02:20 PM
-- Server version: 5.6.17
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `buysana`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
`address_id` int(10) unsigned NOT NULL COMMENT 'Database Generated',
  `address_label` varchar(255) DEFAULT NULL COMMENT 'eg. My Home, My Work ect.',
  `address_street` varchar(255) NOT NULL,
  `address_neighborhod` varchar(255) DEFAULT NULL COMMENT 'Not all addresses have a neighborhood',
  `address_city` varchar(255) NOT NULL,
  `address_state` varchar(255) DEFAULT NULL COMMENT 'Not all addresses have a state',
  `address_region` varchar(255) DEFAULT NULL COMMENT 'Not all addresses have a region',
  `address_country` varchar(255) NOT NULL COMMENT 'eg. PH, US etc.',
  `address_postal` varchar(255) NOT NULL COMMENT 'Postal zip code',
  `address_landmarks` varchar(255) DEFAULT NULL COMMENT 'Informal landmarks',
  `address_latitude` float(10,10) NOT NULL DEFAULT '0.0000000000' COMMENT 'Application Generated',
  `address_longitude` float(10,10) NOT NULL DEFAULT '0.0000000000' COMMENT 'Application Generated',
  `address_public` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Can it be publicly listed ?',
  `address_active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Do not delete rows',
  `address_type` varchar(255) DEFAULT NULL COMMENT 'General usage type',
  `address_flag` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'General usage flag',
  `address_created` datetime NOT NULL COMMENT 'System Generated',
  `address_updated` datetime NOT NULL COMMENT 'System Generated'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app`
--

CREATE TABLE IF NOT EXISTS `app` (
`app_id` int(10) unsigned NOT NULL COMMENT 'Database Generated',
  `app_name` varchar(255) NOT NULL COMMENT 'Name of App',
  `app_domain` varchar(255) DEFAULT NULL COMMENT 'eg. example.com',
  `app_token` varchar(255) NOT NULL COMMENT 'System Generated',
  `app_secret` varchar(255) NOT NULL COMMENT 'System Generated',
  `app_permissions` text NOT NULL COMMENT 'See permissions.json for options',
  `app_website` varchar(255) DEFAULT NULL COMMENT 'eg. http://example.com/',
  `app_active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Do not delete rows',
  `app_type` varchar(255) DEFAULT NULL COMMENT 'General usage type',
  `app_flag` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'General usage flag',
  `app_created` datetime NOT NULL COMMENT 'System Generated',
  `app_updated` datetime NOT NULL COMMENT 'System Generated'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_profile`
--

CREATE TABLE IF NOT EXISTS `app_profile` (
  `app_profile_app` int(10) unsigned NOT NULL,
  `app_profile_profile` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
`auth_id` int(10) unsigned NOT NULL COMMENT 'Database Generated',
  `auth_slug` varchar(255) NOT NULL COMMENT 'can be an email or slug',
  `auth_password` varchar(255) NOT NULL COMMENT 'md5 hash',
  `auth_token` varchar(255) NOT NULL COMMENT 'System Generated',
  `auth_secret` varchar(255) NOT NULL COMMENT 'System Generated',
  `auth_permissions` text NOT NULL COMMENT 'See permissions.json for options',
  `auth_facebook_token` varchar(255) DEFAULT NULL COMMENT 'Facebook access token',
  `auth_facebook_secret` varchar(255) DEFAULT NULL COMMENT 'Facebook access secret',
  `auth_linkedin_token` varchar(255) DEFAULT NULL COMMENT 'LinkedIn access token',
  `auth_linkedin_secret` varchar(255) DEFAULT NULL COMMENT 'LinkedIn access secret',
  `auth_twitter_token` varchar(255) DEFAULT NULL COMMENT 'Twitter access token',
  `auth_twitter_secret` varchar(255) DEFAULT NULL COMMENT 'Twitter access secret',
  `auth_google_token` varchar(255) DEFAULT NULL COMMENT 'Google access token',
  `auth_google_secret` varchar(255) DEFAULT NULL COMMENT 'Google access secret',
  `auth_active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Do not delete rows',
  `auth_type` varchar(255) DEFAULT NULL COMMENT 'General usage type',
  `auth_flag` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'General usage flag',
  `auth_created` datetime NOT NULL COMMENT 'System Generated',
  `auth_updated` datetime NOT NULL COMMENT 'System Generated'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_profile`
--

CREATE TABLE IF NOT EXISTS `auth_profile` (
  `auth_profile_auth` int(10) unsigned NOT NULL,
  `auth_profile_profile` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
`file_id` int(10) unsigned NOT NULL COMMENT 'Database Generated',
  `file_link` varchar(255) NOT NULL COMMENT 'eg. http://example.com/file.jpg',
  `file_path` varchar(255) DEFAULT NULL COMMENT 'Real file name and path',
  `file_mime` varchar(255) NOT NULL COMMENT 'Mime type',
  `file_active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Do not delete rows',
  `file_type` varchar(255) DEFAULT NULL COMMENT 'General usage type',
  `file_flag` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'General usage flag',
  `file_created` datetime NOT NULL COMMENT 'System Generated',
  `file_updated` datetime NOT NULL COMMENT 'System Generated'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
`profile_id` int(10) unsigned NOT NULL COMMENT 'Database Generated',
  `profile_name` varchar(255) NOT NULL COMMENT 'Full name of person',
  `profile_email` varchar(255) DEFAULT NULL COMMENT 'Email of person',
  `profile_phone` varchar(255) DEFAULT NULL COMMENT 'Phone number',
  `profile_detail` text,
  `profile_company` varchar(255) DEFAULT NULL COMMENT 'Where they work',
  `profile_job` varchar(255) DEFAULT NULL COMMENT 'Job title',
  `profile_gender` varchar(255) DEFAULT NULL COMMENT 'male or female',
  `profile_birth` datetime DEFAULT NULL,
  `profile_website` varchar(255) DEFAULT NULL COMMENT 'Personal website',
  `profile_facebook` varchar(255) DEFAULT NULL COMMENT 'Facebook website link',
  `profile_linkedin` varchar(255) DEFAULT NULL COMMENT 'LinkedIn website link',
  `profile_twitter` varchar(255) DEFAULT NULL COMMENT 'Twitter website link',
  `profile_google` varchar(255) DEFAULT NULL COMMENT 'Google website link',
  `profile_reference` varchar(255) DEFAULT NULL COMMENT 'Reference usually related to app',
  `profile_active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Do not delete rows',
  `profile_type` varchar(255) DEFAULT NULL COMMENT 'General usage type',
  `profile_flag` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'General usage flag',
  `profile_created` datetime NOT NULL COMMENT 'System Generated',
  `profile_updated` datetime NOT NULL COMMENT 'System Generated'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_address`
--

CREATE TABLE IF NOT EXISTS `profile_address` (
  `profile_address_profile` int(10) unsigned NOT NULL,
  `profile_address_address` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_file`
--

CREATE TABLE IF NOT EXISTS `profile_file` (
  `profile_file_profile` int(10) unsigned NOT NULL,
  `profile_file_file` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
`session_id` int(10) unsigned NOT NULL COMMENT 'Database Generated',
  `session_token` varchar(255) NOT NULL COMMENT 'System Generated',
  `session_secret` varchar(255) NOT NULL COMMENT 'System Generated',
  `session_permissions` text NOT NULL COMMENT 'See permissions.json for options',
  `session_status` varchar(255) NOT NULL DEFAULT 'PENDING' COMMENT 'eg. PENDING, ACCESS etc.',
  `session_active` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Do not delete rows',
  `session_type` varchar(255) DEFAULT NULL COMMENT 'General usage type',
  `session_flag` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'General usage flag',
  `session_created` datetime NOT NULL COMMENT 'System Generated',
  `session_updated` datetime NOT NULL COMMENT 'System Generated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session_app`
--

CREATE TABLE IF NOT EXISTS `session_app` (
  `session_app_session` int(10) unsigned NOT NULL,
  `session_app_app` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session_auth`
--

CREATE TABLE IF NOT EXISTS `session_auth` (
  `session_auth_session` int(10) unsigned NOT NULL,
  `session_auth_auth` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
 ADD PRIMARY KEY (`address_id`), ADD KEY `address_city` (`address_city`), ADD KEY `address_state` (`address_state`), ADD KEY `address_country` (`address_country`), ADD KEY `address_postal` (`address_postal`), ADD KEY `address_latitude` (`address_latitude`), ADD KEY `address_longitude` (`address_longitude`), ADD KEY `address_public` (`address_public`), ADD KEY `address_active` (`address_active`), ADD KEY `address_type` (`address_type`), ADD KEY `address_flag` (`address_flag`), ADD KEY `address_created` (`address_created`), ADD KEY `address_updated` (`address_updated`);

--
-- Indexes for table `app`
--
ALTER TABLE `app`
 ADD PRIMARY KEY (`app_id`), ADD KEY `app_token` (`app_token`), ADD KEY `app_secret` (`app_secret`), ADD KEY `app_active` (`app_active`), ADD KEY `app_type` (`app_type`), ADD KEY `app_flag` (`app_flag`), ADD KEY `app_created` (`app_created`), ADD KEY `app_updated` (`app_updated`);

--
-- Indexes for table `app_profile`
--
ALTER TABLE `app_profile`
 ADD PRIMARY KEY (`app_profile_app`,`app_profile_profile`), ADD KEY `profile_id_idx` (`app_profile_profile`);

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
 ADD PRIMARY KEY (`auth_id`), ADD UNIQUE KEY `auth_slug` (`auth_slug`), ADD KEY `auth_password` (`auth_password`), ADD KEY `auth_token` (`auth_token`), ADD KEY `auth_secret` (`auth_secret`), ADD KEY `auth_active` (`auth_active`), ADD KEY `auth_type` (`auth_type`), ADD KEY `auth_flag` (`auth_flag`), ADD KEY `auth_created` (`auth_created`), ADD KEY `auth_updated` (`auth_updated`);

--
-- Indexes for table `auth_profile`
--
ALTER TABLE `auth_profile`
 ADD PRIMARY KEY (`auth_profile_auth`,`auth_profile_profile`), ADD KEY `profile_id_idx` (`auth_profile_profile`);

--
-- Indexes for table `file`
--
ALTER TABLE `file`
 ADD PRIMARY KEY (`file_id`), ADD KEY `file_mime` (`file_mime`), ADD KEY `file_active` (`file_active`), ADD KEY `file_type` (`file_type`), ADD KEY `file_flag` (`file_flag`), ADD KEY `file_created` (`file_created`), ADD KEY `file_updated` (`file_updated`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
 ADD PRIMARY KEY (`profile_id`), ADD KEY `profile_name` (`profile_name`), ADD KEY `profile_email` (`profile_email`), ADD KEY `profile_company` (`profile_company`), ADD KEY `profile_gender` (`profile_gender`), ADD KEY `profile_birth` (`profile_birth`), ADD KEY `profile_reference` (`profile_reference`), ADD KEY `profile_active` (`profile_active`), ADD KEY `profile_type` (`profile_type`), ADD KEY `profile_flag` (`profile_flag`), ADD KEY `profile_created` (`profile_created`), ADD KEY `profile_updated` (`profile_updated`);

--
-- Indexes for table `profile_address`
--
ALTER TABLE `profile_address`
 ADD PRIMARY KEY (`profile_address_profile`,`profile_address_address`), ADD KEY `address_id_idx` (`profile_address_address`);

--
-- Indexes for table `profile_file`
--
ALTER TABLE `profile_file`
 ADD PRIMARY KEY (`profile_file_profile`,`profile_file_file`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
 ADD PRIMARY KEY (`session_id`), ADD KEY `session_token` (`session_token`), ADD KEY `session_secret` (`session_secret`), ADD KEY `session_status` (`session_status`), ADD KEY `session_active` (`session_active`), ADD KEY `session_type` (`session_type`), ADD KEY `session_flag` (`session_flag`), ADD KEY `session_created` (`session_created`), ADD KEY `session_updated` (`session_updated`);

--
-- Indexes for table `session_app`
--
ALTER TABLE `session_app`
 ADD PRIMARY KEY (`session_app_session`,`session_app_app`), ADD KEY `app_id_idx` (`session_app_app`);

--
-- Indexes for table `session_auth`
--
ALTER TABLE `session_auth`
 ADD PRIMARY KEY (`session_auth_session`,`session_auth_auth`), ADD KEY `auth_id_idx` (`session_auth_auth`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
MODIFY `address_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Database Generated';
--
-- AUTO_INCREMENT for table `app`
--
ALTER TABLE `app`
MODIFY `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Database Generated';
--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
MODIFY `auth_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Database Generated';
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
MODIFY `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Database Generated';
--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
MODIFY `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Database Generated';
--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
MODIFY `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Database Generated';