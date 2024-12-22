<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Contracts\BaseRepository;
use App\Models\User;

/**
 * UserRepository.
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var User $user Instance of User model.
     */
    protected User $model;

    /**
     * Constructor.
     *
     * @param  User  $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findOne(int $id): mixed
    {
        return $this->model->find($id);
    }

    public function findAll(): mixed
    {
        return $this->model->get();
    }

    public function insert(array $data = []): mixed
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data = []): mixed
    {
        $model = $this->model->findOrFail($id);

        return $model->update($data);
    }

    public function delete(int $id): int
    {
        return $this->model->destroy($id);
    }
}
