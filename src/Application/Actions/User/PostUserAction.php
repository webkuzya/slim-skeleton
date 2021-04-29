<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class PostUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $postUser = $this->getFormData();

        $user = $this->userRepository->insertUser($postUser);

        $this->logger->info("New user added.");

        return $this->respondWithData($user, 201);
    }
}
