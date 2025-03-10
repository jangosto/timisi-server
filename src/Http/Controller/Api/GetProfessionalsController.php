<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Api;

use Domain\Model\User\UserCriteria;
use Domain\Model\User\Users;
use Domain\Query\GetUsersQuery;
use Infrastructure\Http\Controller\QueryBusController;
use Infrastructure\Http\Transformer\UsersTransformer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetProfessionalsController extends QueryBusController
{
    #[Route(
        '/api/professional/',
        name: 'api_get_professionals',
        methods: ['GET']
    )]
    public function __invoke(): Response
    {
        $professionals = new Users(array_merge(
            $this->ask(new GetUsersQuery(
                UserCriteria::createEmpty()
                    ->filterByRole('ROLE_MANAGER')
            ))->toArray(),
            $this->ask(new GetUsersQuery(
                UserCriteria::createEmpty()
                    ->filterByRole('ROLE_SUPER_MANAGER')
            ))->toArray()
        ));

        return new Response(
            json_encode(UsersTransformer::usersToArray($professionals)),
            Response::HTTP_OK
        );
    }
}
