<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\User\User;
use Domain\Model\User\Users;

class UsersTransformer implements TransformerInterface
{
    public static function usersToArray(Users $users): array
    {
        return array_map(
            fn (User $user) => UserTransformer::userToArray($user),
            $users->toArray()
        );
    }
}
