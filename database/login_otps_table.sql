-- Table for storing login OTPs
-- Run this SQL to create the login_otps table

CREATE TABLE IF NOT EXISTS `login_otps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(100) NOT NULL COMMENT 'Passbook number, ID number, or cell number',
  `otp` varchar(6) NOT NULL COMMENT '6-digit OTP code',
  `member_id` int(11) NOT NULL COMMENT 'Reference to members table',
  `cellnumber` varchar(20) NOT NULL COMMENT 'Cell number where OTP was sent',
  `expires_at` datetime NOT NULL COMMENT 'OTP expiration time (5 minutes from creation)',
  `created_at` datetime NOT NULL COMMENT 'When OTP was created',
  `used` tinyint(1) DEFAULT 0 COMMENT '0 = not used, 1 = used',
  `used_at` datetime DEFAULT NULL COMMENT 'When OTP was used',
  PRIMARY KEY (`id`),
  KEY `identifier` (`identifier`),
  KEY `otp` (`otp`),
  KEY `member_id` (`member_id`),
  KEY `expires_at` (`expires_at`),
  KEY `used` (`used`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores OTP codes for member login';





