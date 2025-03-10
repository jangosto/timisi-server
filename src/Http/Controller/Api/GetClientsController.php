<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Api;

use Domain\Model\User\UserCriteria;
use Domain\Query\GetUsersQuery;
use Infrastructure\Http\Controller\QueryBusController;
use Infrastructure\Http\Transformer\UsersTransformer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GetClientsController extends QueryBusController
{
    #[Route(
        '/api/client/',
        name: 'api_get_clients',
        methods: ['GET']
    )]
    public function __invoke(): Response
    {
        $clients = $this->ask(new GetUsersQuery(
            UserCriteria::createEmpty()
                ->filterByRole('ROLE_USER')
        ));

        return new Response(
            json_encode(UsersTransformer::usersToArray($clients)),
            Response::HTTP_OK
        );
    }
}
