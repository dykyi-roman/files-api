<?php

namespace Dykyi;

use Psr\Http\Message\ResponseInterface;
use Dykyi\Exception\ClientException;


/**
 * Class ResponseDataExtractor
 */
class ResponseDataExtractor implements ResponseDataInterface
{
    /**
     * @param ResponseInterface $response
     *
     * @throws \RuntimeException
     *
     * @return object
     */
    public function extract(ResponseInterface $response): object
    {
        $responseBody = $response->getBody()->getContents();
        $rawDecoded = json_decode($responseBody);

        if ($rawDecoded === null) {
            $oneLineResponseBody = str_replace("\n", '\n', $responseBody);
            throw new ClientException(sprintf("Can't decode response: %s", $oneLineResponseBody));
        }

        return $rawDecoded;
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws \RuntimeException
     *
     * @return \stdClass
     */
    public function extractFile(ResponseInterface $response)
    {
        $responseBody = $response->getBody()->getContents();
        $rawDecoded = json_decode($responseBody);

        if ($rawDecoded === null) {
            $oneLineResponseBody = str_replace("\n", '\n', $responseBody);
            throw new ClientException(sprintf("Can't decode response: %s", $oneLineResponseBody));
        }

        return new Response(file_get_contents($rawDecoded->path), 200, [
                'X-Accel-Redirect' => "/mogdl/?" . $rawDecoded->path,
                'Content-type' => 'application/octet-stream',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $rawDecoded->name)]
        );
    }
}