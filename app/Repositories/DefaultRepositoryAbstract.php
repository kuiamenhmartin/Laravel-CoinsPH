<?php

namespace App\Repositories;

abstract class DefaultRepositoryAbstract
{
    /**
    * Return all records
    *
    * @return Illuminate\Database\Eloquent\Collection
    */
    public function all()
    {
        $this->model->all();
    }
}

//https://medium.com/@NahidulHasan/understanding-use-of-interface-and-abstract-class-9a82f5f15837
