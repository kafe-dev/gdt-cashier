<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Class BaseRepository.
 *
 * BaseRepository provides a convenient place for loading components
 * and performing functions that are needed by all your repositories.
 *
 * Extend this class in any new repositories:
 *
 *  ```
 *     class UserRepository extends BaseRepository
 *  ```
 *
 *  For security be sure to declare any new methods as protected or private.
 */
abstract class BaseRepository
{
    /**
     * Find one.
     *
     * @param  int  $id  Object ID
     *
     * @return mixed
     */
    abstract public function findOne(int $id): mixed;

    /**
     * Find all.
     *
     * @return mixed
     */
    abstract public function findAll(): mixed;

    /**
     * Insert a new non-existing record.
     *
     * @param  array  $data  Dataset to insert
     *
     * @return mixed
     */
    abstract public function insert(array $data = []): mixed;

    /**
     * Update an existing record.
     *
     * @param  int  $id  Object ID
     * @param  array  $data  Dataset to update
     *
     * @return mixed
     */
    abstract public function update(int $id, array $data = []): mixed;

    /**
     * Delete an existing record.
     *
     * @param  int  $id  Object ID
     *
     * @return mixed
     */
    abstract public function delete(int $id): mixed;

}
