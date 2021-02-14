CREATE TABLE `users` (
	`id` int(32) unsigned NOT NULL AUTO_INCREMENT,
	`email` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`name` varchar(255) NOT NULL,
	`created` DATETIME NOT NULL,
	`updated` DATETIME DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY (`email`)
) DEFAULT CHARSET=utf8mb4;
