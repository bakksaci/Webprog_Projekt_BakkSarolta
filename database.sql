-- -----------------------------------------------------
-- Schema konyvtar
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `konyvtar` DEFAULT CHARACTER SET utf8 ;
USE `konyvtar` ;

-- -----------------------------------------------------
-- Table `konyvtar`.`books`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `konyvtar`.`books` (
`id` INT NOT NULL AUTO_INCREMENT,
`title` VARCHAR(255) NOT NULL,
`author` VARCHAR(255) NOT NULL,
`category` VARCHAR(255) NULL,
`status` TINYINT NOT NULL DEFAULT 1,
`img` varchar(255) DEFAULT 'https://freepngimg.com/save/11761-book-png-9/512x512',
PRIMARY KEY (`id`),
UNIQUE INDEX `id_UNIQUE` (`id` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `konyvtar`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `konyvtar`.`users` (
`id` INT NOT NULL AUTO_INCREMENT,
`name` VARCHAR(45) NOT NULL,
`email` VARCHAR(255) NOT NULL,
`password` CHAR(60) NULL,
`admin` TINYINT DEFAULT 0,
PRIMARY KEY (`id`),
UNIQUE INDEX `email_UNIQUE` (`email` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `konyvtar`.`borrows`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `konyvtar`.`borrows` (
`id` INT NOT NULL AUTO_INCREMENT,
`book_id` INT NULL,
`user_id` INT NULL,
`borrow_from` DATE NULL,
`borrow_to` DATE NULL,
`returned` INT DEFAULT 0,
PRIMARY KEY (`id`),
INDEX `borrows_book_idx` (`book_id` ASC),
INDEX `borrows_user_idx` (`user_id` ASC),
CONSTRAINT `fk_book`
FOREIGN KEY (`book_id`)
REFERENCES `konyvtar`.`books` (`id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION,
CONSTRAINT `fk_user`
FOREIGN KEY (`user_id`)
REFERENCES `konyvtar`.`users` (`id`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Data insertion for books
-- -----------------------------------------------------
INSERT INTO `konyvtar`.`books` (`author`, `title`, `category`) VALUES
('Joanne Kathleen Rowling', 'Harry Potter', 'Fantasy'),
('Erich Kästner', 'A két Lotti', 'Gyermekkönyv'),
('Ian McEwan', 'Vágy és vezeklés', 'Regény'),
('Michel Houellebecq', 'A csúcson', 'Regény'),
('Paul Auster', 'Az illúziók könyve', 'Regény'),
('Carol Shields', 'Norah, gyere haza!', 'Regény'),
('Jonathan Safran Foer', 'Minden vilángol', 'Regény'),
('José Saramago', 'Az embermás', 'Regény'),
('Sarah Waters', 'A szobalány', 'Regény'),
('Paula Hawkins', 'A lány a vonaton', 'Krimi'),
('Rohinton Mistry', 'Családi ügyek', 'Regény'),
('Lois Lowry', 'A Kiválasztott', 'Gyermekkönyv'),
('Suzanne Collins', 'Az Éhezők Viadala', 'Fantasy'),
('J.R.R. Tolkien', 'A Gyűrűk Ura', 'Fantasy'),
('Margaret Atwood', 'A Szolgálólány Meséje', 'Science Fiction'),
('F. Scott Fitzgerald', 'A Nagy Gatsby', 'Regény'),
('Mihail Bulgakov', 'A Mester és Margarita', 'Fantasy'),
('Paulo Coelho', 'Az Alkimista', 'Regény'),
('George Orwell', '1984', 'Science Fiction'),
('J.R.R. Tolkien', 'A Gyűrűk Útja', 'Fantasy');

-- -----------------------------------------------------
-- Data insertion for users
--password = 'jelszo'
-- -----------------------------------------------------
INSERT INTO `konyvtar`.`users` (`name`, `email`, `password`, `admin`) VALUES
('admin', 'admin@konyvtar.com', '$2y$10$G3YSYw0iWy8LbF2gyko4s..5Yc8K00diIS0P6Ofqu2d3JCavSpPk6', 1),
('olvaso', 'olvaso@konyvtar.com', '$2y$10$G3YSYw0iWy8LbF2gyko4s..5Yc8K00diIS0P6Ofqu2d3JCavSpPk6', 0);
