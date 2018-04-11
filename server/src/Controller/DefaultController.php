<?php

namespace App\Controller;

use function Couchbase\defaultDecoder;
use Swagger\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends Controller
{
    public function index()
    {
        $swagger = \Swagger\scan($this->get('kernel')->getRootDir());

        file_put_contents('swagger.json', $swagger);

        return $this->render('default/api.html.twig');
    }
}
