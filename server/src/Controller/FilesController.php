<?php

namespace App\Controller;

use App\Storage\MogileFileStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use App\Entity\Files;

/**
 * @SWG\Info(title="Dykyi File API", version="1.0")
 *
 * @SWG\Get(
 *     path="/file/{id}",
 *     summary="Get file by ID",
 *     @SWG\Response(response="200", description="Get file by ID"),
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Post(
 *     path="/file",
 *     summary="Upload the new file",
 *     @SWG\Response(response="200", description="Upload the new file"),
 *     security={{"Dykyi":{}}}
 * )
 *
 * @SWG\Delete(
 *     path="/file/{id}",
 *     summary="Delete file by ID",
 *     @SWG\Parameter(
 *          name="body",
 *          in="body",
 *          required=true,
 *          @SWG\Schema(
 *              @SWG\Property(
 *                  property="id",
 *                  type="integer"
 *              )
 *          )
 *     ),
 *     @SWG\Response(response="200", description="Delete file by ID"),
 *     security={{"Dykyi":{}}}
 * )
 *
 */

/**
 * Files controller.
 *
 * @Route("/")
 */
class FilesController extends Controller
{
    /**
     * Lists all Files.
     * @FOSRest\Get("/files")
     *
     * @return View
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function getFilesAction()
    {
        $repository = $this->getDoctrine()->getRepository(Files::class);

        $files = $repository->findBy(['limit' => 30]);

        return View::create($files, Response::HTTP_OK);
    }

    /**
     * Get File By ID
     * @FOSRest\Get("/file/{id}")
     *
     * @param int $id
     * @return array|Response
     * @throws \Exception
     */
    public function getFileByIdAction(int $id)
    {
        $result = [];
        $storage = new MogileFileStorage();
        $repository = $this->getDoctrine()->getRepository(Files::class);

        /** @var Files $file */
        $file = $repository->find(['file_id' => $id]);
        if (null === $file) {
            return $result;
        }

        try {
            $result = $storage->get($file);
        } catch (\Exception $e) {
            return new JsonResponse($result, Response::HTTP_INSUFFICIENT_STORAGE);
        }

        return new JsonResponse($result, Response::HTTP_OK);
    }

    /**
     * Create new file
     *
     * @FOSRest\Post("/file")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \RuntimeException
     */
    public function postFileAction(Request $request): JsonResponse
    {
        $file = new Files();
        $file->setName($request->get('name'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($file);
        $em->flush();

        $storage = new MogileFileStorage();
        $storage->create($request->get('filePath'), $file->getId());

        return new JsonResponse(['id' => $file->getId()], Response::HTTP_OK);
    }

    /**
     * Delete file by Id
     *
     * @FOSRest\Delete("/file/{id}")
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function deleteFileByIdAction(int $id): JsonResponse
    {
        $storage = new MogileFileStorage();
        if ($storage->delete($id))
        {
            $em = $this->getDoctrine()->getManager();
            /** @var Files $file */
            $file = $em->getRepository('Files')->find($id);
            if (!$file) {
                throw $this->createNotFoundException('Files not fount ['.$id.']');
            }

            $file->setActive(0);
            $em->flush();
        }

        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
}