<?php

namespace Dykyi;

use Dykyi\Exception\ClientException;
use Dykyi\Exception\TransferException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VaultClient
 * @package Dykyi
 */
class FileClient extends BasicClient
{

    private $options = [];

    /**
     * FileClient constructor.
     * @param ClientInterface $client
     * @param ResponseDataExtractor $extractor
     * @param string $env
     * @param null $logger
     * @throws Exception\ClientException
     */
    public function __construct(ClientInterface $client, ResponseDataExtractor $extractor, $namespace, $env, $logger = null)
    {
        (new Dotenv('../'))->load();
        parent::__construct($client, $extractor, $logger);
        $this->setDefaultOptions($namespace, $env);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws \Dykyi\Exception\ClientException
     */
    private function assertResponce(RequestInterface $request, ResponseInterface $response)
    {
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $message = sprintf('Something went wrong when calling vault (%s - %s).', $response->getStatusCode(),
                $response->getReasonPhrase());
            $this->logger->error($message);

            throw new ClientException($message, $response->getStatusCode(), $request);
        }
    }

    /**
     * Default options take from .env file
     *
     * @param string $namespace
     * @param string $env
     */
    private function setDefaultOptions(string $namespace, string $env)
    {
        // set default options
        $this->options = [
            'verify' => false,
            'base_uri'    => getenv('FILE_API'),
            'http_errors' => false,
            'headers'     => [
                'env' => $env,
                'namespace' => $namespace,
                'Content-Type'  => 'application/json',
            ],
        ];
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Dykyi\Exception\ClientException
     */
    private function send(RequestInterface $request, $options = [])
    {
        $this->logger->info(sprintf('%s "%s"', $request->getMethod(), $request->getUri()));

        try {
            $response = $this->client->send($request, $options);
            $this->assertResponce($request, $response);
        } catch (TransferException $e) {
            $message = sprintf('Something went wrong when calling vault (%s).', $e->getMessage());
            $this->logger->error($message);
        } catch (GuzzleException $e) {
            var_dump($e->getMessage()); die();
        }

        return $response;
    }

    /**
     * @param int $id
     * @return \stdClass
     */
    public function get(int $id)
    {
        $response = $this->send(new Request('GET', '/file/'.$id), $this->options);
        return $this->extractor->extractFile($response);
    }

    /**
     * @param array $data
     * @return object
     * @throws \RuntimeException
     */
    public function write(array $data): object
    {
        $response = $this->send(new Request('POST', '/file', [], json_encode($data)), $this->options);
        return $this->extractor->extract($response);
    }

    /**
     * @param int $id
     * @return object
     * @throws \RuntimeException
     */
    public function delete(int $id): object
    {
        $response = $this->send(new Request('DELETE', '/file/'.$id), $this->options);
        return $this->extractor->extract($response);
    }
}
