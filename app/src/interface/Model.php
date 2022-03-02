<?php

interface Model{
    public static function get($id, ?array $fields);
    public static function getAll();
    public static function delete($table,$id);
    public static function insert();
    public static function deleteAll();
    public static function update($id, array $fields);
}
