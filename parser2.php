<?php

use App\Service\Migration;
use App\Service\Seeder;

require_once 'App/Autoloader.php';
Autoloader::register();

//(new Migration())->create('create_brands');
//(new Migration())->create('create_categories');
//(new Migration())->create('create_colours');
//(new Migration())->create('create_goods');

//(new Migration())->migrate();
(new Seeder())->seed('brands');

ini_set('auto_detect_line_endings',TRUE);
$handle = fopen('test.csv','r');
while (($data = fgetcsv($handle) ) !== FALSE) {
    $data = str_replace('"', '', $data);
    //var_dump(current($data));
}
ini_set('auto_detect_line_endings',FALSE);


//    '/^(?P<type>[а-яА-Я\s]+)\s+(?P<brand>[a-zA-Z]+)\s+(?P<description>[а-яА-Я\s\.\(\)]+)\s+(?P<model>[a-zA-Z\s]+)\s+(?P<size>[a-zA-Z0-9]+)\s+(?P<age>[A-Z]*)\s*\(\s*(?P<vendor_code>)\S\d+\s*|\d+\s*\)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<vendor_code>)\S\d+|\d+\)\s+(?P<type>[а-яА-Я\s]+)\s+(?P<description>[а-яА-Я\s\.\(\)]+)\s+(?P<model>[a-zA-Z\s]+)\s+(?P<age>[A-Z]+)\s+(?P<colour>[а-яА-Я]+)\s+-\s+(?P<size>[A-Z]+)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я\s]+)\s+(?P<description>[а-яА-Я\s\.\(\)]+)\s+(?P<model>[a-zA-Z\s]+)\s+(?P<code>\d+)\s(?P<colour>[а-яА-Я\/-]+)\s+-\s+(?P<size>[A-Z]+)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я]+)\s+(?P<description>[а-яА-Я\s\.\(\)]+)\s+(?P<model>[a-zA-Z\s]+)\s+(?P<code>\d+)\s+\((?P<colour>[а-я\/-]+)\)\s+-\s+(?P<size>[A-Z]+)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я]+)\s+(?P<description>[а-яА-Я\s\.\(\)]+)\s+(?P<model>[a-zA-Z\s]+)\s+(?P<code>\d+)\s+(?P<colour>[а-я\/-]+)\s+-\s+(?P<size>\d+)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я]+)\s+(?P<description>[а-яА-Я]+)\s+(?P<model>[a-zA-Z\d]+)\s+(?P<code>[A-Z\d]*)\s*(?P<age>[A-Z]*)\s*(?P<rigidity>[a-zA-Z]*)\s*(?P<length>\d*)\s*\((?P<bend>[A-Z]\d+)\)\s+-\s+(?P<grip>[A-Z])$/u',
//    '/^(?P<type>[а-яА-Я]+)\s+(?P<brand>[a-zA-Z]+)\s+(?P<description>.*)\s+(?P<size>\d+)\s+(?P<colour>[a-zA-Z]*)\s*(?P<age>[A-Z]+)\s*$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я\s]+)\s+(?P<description>[А-Яа-я]+)\s+(?P<model>[a-zA-Z\s]+)\s+(?P<colour>[а-яА-Я]+)\s-\s+(?P<size>[A-Z]+)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я\s]+)\s+(?P<model>[a-zA-Z\s]+)-\s+(?P<size>[A-Z]+)$/u',
//    '/^(?P<brand>[a-zA-Z]+)\s+(?P<type>[а-яА-Я\s]+)\s+(?P<description>[А-Яа-я]+)\s+(?P<code>\d+)\s+(?P<model>[a-zA-Z\s]+)\s*-\s*(?P<size>[0-9\.,]+)$/u',
//    '/^(?P<type>[а-яА-Я]+)\s+(?P<description>[А-Яа-я]+)\s+(?P<brand>[a-zA-Z]+)\s*(?P<model>[a-zA-Z\s\d]*)\s*-\s*(?P<size>[\d\.,]+)$/u',
//    '/^(?P<type>[а-яА-Я\s]+)\s+(?P<age>[A-Z]+|[A-Z]+\s[а-я]+|[а-я]+)\s*(?P<model>[а-яА-Я]*)\s*(?P<colour>[а-яА-Я\.,\/-]*)\s*(?P<model1>[A-Za-z]*)$/u',

//
//foreach ($data as $key => $item) {
//    foreach ($patterns as $pattern) {
//        if (preg_match($pattern, $item, $matches)) {
//            $count++;
//            unset($data[$key]);
//
//            break;
//        }
//    }
//}
//file_put_contents('result.csv', $data);
//echo "$count\n";
