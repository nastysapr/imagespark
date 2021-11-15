<?php

class CreateGoods
{
    public function up(): string
    {
        return "CREATE TABLE goods (
            id int AUTO_INCREMENT,
            catalog_id int NOT NULL,
            vendor_code varchar(255),
            brand varchar(255),
            model varchar(255),
            name varchar(255) NOT NULL,
            size varchar(255),
            colour_id int,
            orientation char,
            PRIMARY KEY (id))";
    }

    public function down(): string
    {
        return "";
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
