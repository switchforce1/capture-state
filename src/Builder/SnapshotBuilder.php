<?php
declare(strict_types=1);

namespace App\Builder;

use App\Entity\Snapshot;
use App\Entity\Source;
use App\Factory\SnapshotFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SnapshotBuilder
{
    private HttpClientInterface $client;
    private SnapshotFactory $snapshotFactory;

    public function __construct(HttpClientInterface $client, SnapshotFactory $snapshotFactory)
    {
        $this->client = $client;
        $this->snapshotFactory = $snapshotFactory;
    }

    public function buildSnapshot(Source $source): ?Snapshot
    {
        if (empty($source)) {
            throw new \Exception('Unable to build snapshot without any source specified');
        }
        if (!filter_var($source->getUrl(), FILTER_VALIDATE_URL)) {
            throw new \Exception(sprintf(
                'Source %s : Url {%s} is not valid. No snapshot can be taken',
                $source->getLabel(),
                (string) $source->getUrl()
            ));
        }
        $snapshot = $this->snapshotFactory->create($source);

        $method = $source->getMethod() ?? Request::METHOD_GET;
        try {
            $response = $this->client->request($method, $source->getUrl(), ['timeout' => -1]);
            $rawContent = $response->getContent();
        } catch (\Exception $exception) {
            return null;
//            throw new \Exception($exception->getMessage());
        }
        $json = json_decode($rawContent, true);
        $snapshot
            ->setData($json)
            ->setRawData($rawContent)
        ;

        return $snapshot;
    }
}