<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Api;

use Domain\Query\GetSessionsQuery;
use Infrastructure\Http\Controller\QueryBusController;
use Infrastructure\Http\CriteriaTransformer\SessionCriteriaTransformer;
use Infrastructure\Http\Transformer\SessionsTransformer;
use Infrastructure\Http\Transformer\Validator\ConstraintViolationErrorsTransformer;
use Infrastructure\Http\ValidatorDTO\GetSessionsRequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetSessionsController extends QueryBusController
{
    #[Route(
        '/api/session/',
        name: 'api_get_sessions',
        methods: ['GET']
    )]
    public function __invoke(Request $request, ValidatorInterface $validator): Response
    {
        $criteriaAsArray = $request->query->all();

        $dto = new GetSessionsRequestDTO($criteriaAsArray);
        $errors = $validator->validate($dto);

        if ($errors->count() > 0) {
            return new Response(
                json_encode(ConstraintViolationErrorsTransformer::constraintViolationsToArray($errors)),
                Response::HTTP_BAD_REQUEST
            );
        }

        $sessions = $this->ask(new GetSessionsQuery(
            SessionCriteriaTransformer::createCriteriaFromArray($criteriaAsArray)
        ));

        return new Response(
            json_encode(SessionsTransformer::sessionsToArray($sessions)),
            Response::HTTP_OK
        );
    }
}
