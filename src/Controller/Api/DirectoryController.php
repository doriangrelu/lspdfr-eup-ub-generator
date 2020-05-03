<?php


namespace App\Controller\Api;


use App\Core\Utility;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DirectoryController extends AbstractApiController
{

    private $services = [];
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        $this->registerService(EupController::class);
    }

    /**
     * @Route("/api/directory", name="api.directory", methods={"GET"})
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function index(): Response
    {
        return $this->buildDirectory();
    }

    /**
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private function buildDirectory(): Response
    {
        $cacheKey = $this->generateCacheKeyHash();
        $fileCacheAdapter = new FilesystemAdapter();
        $services = $this->services;
        $urlGenerator = $this->urlGenerator;
        return $fileCacheAdapter->get($cacheKey, function () use ($services, $urlGenerator) {
            $directory = [];
            foreach ($services as $service) {
                $directory = array_merge($directory, $this->generateEntry($service::getDirectory($urlGenerator)));
            }
            return $this->json($directory);
        });
    }


    private function generateEntry(array $infos): array
    {
        $entries = [];
        foreach ($infos as $info) {
            $methods = $info['methods'] ?? ['GET'];
            $methods = array_map(function ($value) {
                return mb_strtoupper($value);
            }, $methods);

            $route = $info['route'] ?? '';
            $urlParams = $info['params'] ?? [];
            $entries[] = [
                'route' => $route,
                'url' => $this->urlGenerator->generate($route, $this->flattenParams($urlParams), UrlGeneratorInterface::ABSOLUTE_URL),
                'methods' => $methods,
            ];
        }
        return $entries;
    }


    private function flattenParams(array $params): array
    {
        $flattenParams = [];
        foreach ($params as $param) {
            $flattenParams[$param] = ":$param:";
        }
        return $flattenParams;
    }

    /**
     * @return string
     */
    private function generateCacheKeyHash(): string
    {
        $prettyName = implode('-', $this->services) . ($_ENV['APP_ENV'] === 'dev' ? Utility::uuid() : '');
        return Utility::hash($prettyName);
    }

    /**
     * @param string $className
     * @return $this
     */
    private function registerService(string $className): self
    {
        if (!in_array($className, $this->services)) {
            $this->services[] = $className;
        }
        return $this;
    }


}