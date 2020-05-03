<?php


namespace App\Controller\Api;


use App\Core\Api\XMLProcessingRunnable;
use App\Core\Interfaces\RestServiceInterface;
use App\Core\IO\File;
use App\Core\Thread\Thread;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EupController extends AbstractApiController implements RestServiceInterface
{


    public function __construct()
    {
    }

    /**
     * @Route("/api/eup/{key}/long-polling", name="api.eup.long_polling", methods={"GET"}, requirements={"key"="[A-Za-z0-9]+|\:key\:"})
     *
     * @param string $key
     * @return Response
     */
    public function isProcessed(string $storageDirectory, string $key): Response
    {
        $tempFile = new File($storageDirectory . '/' . $key . '.tmp');
        if ($tempFile->fileExists() === false) {
            return $this->json($this->responseStatusKo('404', 'not found'));
        }
        set_time_limit(1200); //infinite, risky also set 20 minutes limit (1200 seconds)
        $xmlFilename = $storageDirectory . '/' . $key . '.xml';
        $runnable = new XMLProcessingRunnable($xmlFilename);
        Thread::await($runnable, 40, 15); //dangerous
        return $this->json($this->responseStatusOk([]));
    }


    public static function getDirectory(): array
    {
        return [
            ['route' => 'api.eup.long_polling', 'params' => ['key']],
        ];
    }
}