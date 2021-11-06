<?php

interface DriverInterface
{
    public function count(string $table);

    public function save(object $record);

    public function deleteRecord(object $record);

    public function findRecordByPK(string $table, string $model, int $id);

    public function findAll(string $table, string $model, string $filter = '', int $offset = 0, int $limit = 0);
}