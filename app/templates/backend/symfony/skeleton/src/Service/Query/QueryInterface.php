<?php
namespace App\Service\Query;

interface QueryInterface
{
    public function getRepository();

    public function search($parameters = []);

    public function findById(int $id);
}
