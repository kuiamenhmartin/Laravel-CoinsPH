<?php

namespace App\Repositories\User;

use App\User;
use App\Repositories\DefaultRepositoryAbstract;
use App\Repositories\DefaultRepositoryInterface;

class UserRepository extends DefaultRepositoryAbstract implements DefaultRepositoryInterface
{
    /**
    * @var Model
    */
    protected $model;

    /**
    * Constructor
    */
    public function __construct(User $model)
    {
      $this->model = $model;
    }

    /**
     * Create User
     * @method create
     * @param  array  $data User data
     * @return collection
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    /**
     * Get's a user by it's Email
     *
     * @param int
     * @return collection
     */
    public function getByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get's a user by it's ID
     *
     * @param int
     * @return collection
     */
    public function get(int $user_id)
    {
        return $this->model->where('id', $user_id)->first();
    }

    /**
     * Deletes a user.
     *
     * @param int
     */
    public function delete(int $user_id)
    {
        $this->model->destroy($user_id);
    }

    /**
     * Updates a user.
     *
     * @param int
     * @param array
     */
    public function update(int $user_id, array $user_data)
    {
        $this->model->find($user_id)->update($user_data);
    }
  }
