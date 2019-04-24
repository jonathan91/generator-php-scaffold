<?php
namespace Application\Repository;

interface AppInterfaceRepository
{
    public function search(array $where = []);
}

