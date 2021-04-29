<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findUserOfId(int $id): User;

    public function insertUser(\stdClass $data): User;

    public function updateUser(\stdClass $data): User;

    public function deleteUser(int $id): void;
}
