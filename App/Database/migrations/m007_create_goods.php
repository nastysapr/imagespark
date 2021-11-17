<?php

class CreateGoods
{
    public function up(): string
    {
        return "CREATE TABLE goods (
            id int AUTO_INCREMENT,
            catalog_id int NOT NULL,
            vendor_code varchar(45) DEFAULT NULL,
            brand_id int DEFAULT NULL,
            model varchar(255) DEFAULT NULL,
            size varchar(45) DEFAULT NULL,
            colour_id int DEFAULT NULL,
            orientation char DEFAULT NULL,
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "DROP TABLE goods";
    }
}

/*
 * Раздел каталога.
Подраздел каталога.
Артикул товара.
Бренд.
Модель.
Наименование товара.
Доступные размеры.
Доступные цвета.
Ориентация право \ лево (для клюшек).
 */
