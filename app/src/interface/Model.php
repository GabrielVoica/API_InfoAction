<?php

interface Model{
    public function get($id, ?array $fields);
    public function getAll();
    public function delete($id);
    public function insert();
    public function deleteAll();
    public function update($id, array $fields);
}
