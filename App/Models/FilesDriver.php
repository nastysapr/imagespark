<?php

namespace App\Models;

use App\Service\Errors;
use DateTime;
use GlobIterator;

class FilesDriver implements DriverInterface
{
    private string $path;

    public function __construct()
    {
        $connectParams = require __DIR__ . '/../../config/db_config.php';
        $this->path = $connectParams['paths']['root'] . $connectParams['paths']['storage'];
    }
    /**
     * TODO!!!!
     */
    /**
     * Подсчет записей в таблице
     */
    public function count(string $table, string $filter): int
    {
        $directory = $this->path . $table . '/';
        $iterator = new GlobIterator($directory . '*');
        return iterator_count($iterator);
    }

    /**
     * Удаляет запись из базы
     */
    public function deleteRecord(object $record): bool
    {
        $filename = str_pad($record->id, 7, 0, STR_PAD_LEFT);
        $directory = $this->path . $record->table . '/';

        return unlink($directory . $filename);
    }

    /*
     * Поиск записи по идентификатору
     */
    public function findRecordByPK(string $table, string $model, int $id): object
    {
        $directory = $this->path . $table . '/';
        $filename = $directory . str_pad($id, 7, 0, STR_PAD_LEFT);

        if (!file_exists($filename)) {
            (new Errors())->notFound();
        }

        $data = unserialize(file_get_contents($filename));
        $record = new $model();

        foreach ($data as $attribute => $value) {
            $record->$attribute = $value;
        }

        return $record;
    }

    /**
     * Возвращает из базы записи в зависимости от параметров
     */
    public function findAll(string $table, string $model, string $filter = '', int $offset = 0, int $limit = 0): array
    {
        $directory = $this->path . $table . '/';
        $iterator = new GlobIterator($directory . '*');

        $entitiesList = [];
        if ($offset > 1) {
            $iterator->seek(($offset - 1) * $limit);
        }

        if (!$limit) {
            $limit = $this->count($table, $filter);
        }

        for ($count = 0; $count < $limit;) {
            if (!$iterator->valid()) {
                break;
            }

            $id = (int) $iterator->getFilename();
            $item = (new $model)->findByPK($id);
            $item->id = $id;

            if ($filter === 'date') {
                $dateDiff = date_diff(new DateTime(), DateTime::createFromFormat('Y-m-d', $item->date));

                if ($dateDiff->days <= 1) {
                    $entitiesList[] = $item;
                    $count++;
                }
            } elseif ($item->login === $filter) {
                $entitiesList[] = $item;
                return $entitiesList;
            }

            $entitiesList[] = $item;
            $count++;

            $iterator->next();
        }

        return $entitiesList;
    }

    /**
     * Вычислить числовой идентификатор для нового объекта
     */
    public function calcId(string $table): ?int
    {
        $directory = $this->path . $table . '/';
        $iterator = new GlobIterator($directory . '*');
        $countFiles = iterator_count($iterator);

        $iterator->seek($countFiles - 1);

        $id = (int) $iterator->getFilename();

        return ++$id;
    }

    /**
     * Сохраняет объект в базе
     */
    public function save(object $record): int
    {
        if (!$record->id) {
            $record->id = $this->calcId($record->table);
        }

        $directory = $this->path . $record->table . '/';
        $filename = str_pad($record->id, 7, 0, STR_PAD_LEFT);
        $file = $directory . $filename;

        $data = serialize($record->attributes);

        file_put_contents($file, $data, 0);

        return $record->id;
    }
}