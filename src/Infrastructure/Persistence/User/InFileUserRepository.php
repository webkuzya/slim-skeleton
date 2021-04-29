<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;

class InFileUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    /**
     * @var string
     */
    private $fileName = "";

    /**
     * InFileUserRepository constructor.
     *
     * @param array|null $users
     */
    public function __construct(array $users = null)
    {
        $this->fileName = dirname($_SERVER['DOCUMENT_ROOT'])."/var/users.arr";

        if (is_file($this->fileName)){
            $this->users = unserialize(file_get_contents($this->fileName));
        }
        else {
            file_put_contents($this->fileName, []);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return $this->users ? $this->users : [];
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        $aId = array_map(function($o) {
            return $o->getId();
        }, $this->users);
        $key = array_search($id, $aId);

        if ($key === false || !isset($this->users[$key])) {
            throw new UserNotFoundException();
        }

        return $this->users[$key];
    }

    /**
     * Insert user
     * @param \stdClass $data
     * @return User
     */
    public function insertUser(\stdClass $data): User
    {
        $aId = array_map(function($o) {
            return $o->getId();
        }, $this->users);
        $maxId = max($aId);

        $user = new User(++$maxId, $data->userName, $data->firstName, $data->lastName);
        $this->users[] = $user;
        file_put_contents($this->fileName, serialize($this->users));

        return $user;
    }

    /**
     * Update user
     * @param \stdClass $data
     * @return User
     */
    public function updateUser(\stdClass $data): User
    {
        $aId = array_map(function($o) {
            return $o->getId();
        }, $this->users);
        $key = array_search($data->id, $aId);

        $user = new User($data->id, $data->userName, $data->firstName, $data->lastName);
        $this->users[$key] = $user;
        file_put_contents($this->fileName, serialize($this->users));

        return $user;
    }

    /**
     * Delete user
     * @param int $id
     */
    public function deleteUser(int $id): void
    {
        $users = array_filter($this->users, function($o) use ($id) {
            return $o->getId() !== $id;
        });

        $this->users = array_values($users);
        file_put_contents($this->fileName, serialize($this->users));
    }
}
