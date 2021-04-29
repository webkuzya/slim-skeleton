<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class PutUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $userId = (int) $this->resolveArg('id');
        $user = $this->userRepository->findUserOfId($userId);

        $postUser = $this->getFormData();

        if ($user->getId() === $postUser->id){

            $user = $this->userRepository->updateUser($postUser);

            $this->logger->info("User of id `${userId}` was updated.");

            return $this->respondWithData($user);
        }
    }
}
