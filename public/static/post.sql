CREATE TABLE `java`.`post`
(
    `id`          INT                                                     NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `content`     TEXT CHARACTER SET utf8 COLLATE utf8_general_ci         NULL     DEFAULT NULL,
    `create_date` TIMESTAMP                                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `public`      TINYINT(1)                                              NOT NULL DEFAULT '0',
	`like`        int default 0 null,
    `category_id`  INT(5)                                                default null null,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;


CREATE TABLE `java`.`group` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE `java`.`user` ( `id` INT NOT NULL AUTO_INCREMENT , `admin-additional` TINYINT(1) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
-- new table blog

create table blog
(
	id serial auto_increment,
	title varchar(200) default null null,
	content text default null null,
	`like` tinyint default 0 null,
	public tinyint default 0 null,
	create_date timestamp default current_timestamp null
);

