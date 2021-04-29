<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\InFileUserRepository;
//use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its implementation
    $containerBuilder->addDefinitions([
        //UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        UserRepository::class => autowire(InFileUserRepository::class),
    ]);
};
