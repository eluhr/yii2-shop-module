-- -----------------------------------------------------
-- Table `sp_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_product` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(80) NOT NULL,
  `is_online` TINYINT(1) NOT NULL DEFAULT 0,
  `rank` INT NOT NULL,
  `description` TEXT(1024) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `title_UNIQUE` (`title` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_variant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_variant` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `title` VARCHAR(80) NOT NULL,
  `thumbnail_image` VARCHAR(128) NOT NULL,
  `is_online` TINYINT(1) NOT NULL DEFAULT 0,
  `rank` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `hex_color` CHAR(9) NOT NULL,
  `stock` INT NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_sp_variant_sp_product_idx` (`product_id` ASC),
  CONSTRAINT `fk_sp_variant_sp_product`
    FOREIGN KEY (`product_id`)
    REFERENCES `sp_product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_filter`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_filter` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(80) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_tag_x_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_tag_x_product` (
  `tag_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`tag_id`, `product_id`),
  INDEX `fk_tag_x_product_product1_idx` (`product_id` ASC),
  INDEX `fk_tag_x_product_tag1_idx` (`tag_id` ASC),
  CONSTRAINT `fk_tag_x_product_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `sp_tag` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tag_x_product_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `sp_product` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_tag_x_filter`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_tag_x_filter` (
  `tag_id` INT NOT NULL,
  `facet_id` INT NOT NULL,
  `show_in_frontend` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`tag_id`, `facet_id`),
  INDEX `fk_tag_x_facet_facet1_idx` (`facet_id` ASC),
  INDEX `fk_tag_x_facet_tag1_idx` (`tag_id` ASC),
  CONSTRAINT `fk_tag_x_facet_tag1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `sp_tag` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tag_x_facet_facet1`
    FOREIGN KEY (`facet_id`)
    REFERENCES `sp_filter` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_order` (
  `id` CHAR(36) NOT NULL,
  `paypal_id` VARCHAR(45) NOT NULL,
  `paypal_token` VARCHAR(45) NULL,
  `paypal_payer_id` VARCHAR(45) NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `surname` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `street_name` VARCHAR(45) NOT NULL,
  `house_number` VARCHAR(45) NOT NULL,
  `postal` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `status` ENUM('RECEIVED','RECEIVED PAID','IN PROGRESS','SHIPPED','FINISHED') NOT NULL DEFAULT 'RECEIVED',
  `paid` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sp_order_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sp_order_item` (
  `order_id` CHAR(36) NOT NULL,
  `variant_id` INT NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `quantity` INT NOT NULL,
  `single_price` DECIMAL(10,2) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`order_id`, `variant_id`),
  INDEX `fk_sp_order_has_sp_variant_sp_variant1_idx` (`variant_id` ASC),
  INDEX `fk_sp_order_has_sp_variant_sp_order1_idx` (`order_id` ASC),
  CONSTRAINT `fk_sp_order_has_sp_variant_sp_order1`
    FOREIGN KEY (`order_id`)
    REFERENCES `sp_order` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sp_order_has_sp_variant_sp_variant1`
    FOREIGN KEY (`variant_id`)
    REFERENCES `sp_variant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
