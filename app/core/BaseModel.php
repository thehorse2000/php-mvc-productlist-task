<?php

abstract class BaseModel extends Database
{
    abstract public function findall();
    abstract public function add();
    abstract public function delete($id);
}