<?php

namespace App\Repositories;

interface DefaultRepositoryInterface
{
  public function create(array $data);
  /**
   * Get's a record by it's ID
   *
   * @param int
   */
  public function get(int $id);

  /**
   * Get's all records.
   *
   * @return mixed
   */
  public function all();

  /**
   * Deletes a record.
   *
   * @param int
   */
  public function delete(int $id);

  /**
   * Updates a record.
   *
   * @param int
   * @param array
   */
  public function update(int $id, array $data);
}

//https://culttt.com/2014/03/17/eloquent-tricks-better-repositories/
