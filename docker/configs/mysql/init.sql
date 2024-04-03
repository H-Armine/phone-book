CREATE DATABASE
IF NOT EXISTS `phone_book` DEFAULT CHARSET 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';
GRANT ALL ON `phone_book`.* TO 'developer'@'%';

FLUSH PRIVILEGES ;
