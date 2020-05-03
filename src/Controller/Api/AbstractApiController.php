<?php


namespace App\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    protected function responseStatus($data = [], int $code = 200, ?string $title = null, bool $paginated = false): array
    {
        $response = [
            'status' => $code >= 200 && $code < 400 ? 200 : 500,
            'data' => $data,
            'paginated' => $paginated,
        ];
        if ($code > 400) {
            $response['title'] = $title;
            $response['type'] = 'Unexpected error.';
            $response['class'] = null;
            $response['trace'] = null;
            $response['detail'] = $data;
            unset($response['data']);
        }

        return $response;
    }


    protected function responseStatusKo(string $title = 'Oups une erreur est survenue', $error = 'Aucune information...')
    {
        return $this->responseStatus($error instanceof \Exception ? $error->getMessage() : $error, 500, $title);
    }

    protected function responseStatusOk($data = [], bool $paginated = false): array
    {
        return $this->responseStatus($data, 200, $paginated);
    }

}