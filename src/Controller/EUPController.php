<?php

namespace App\Controller;

use App\Core\IO\File;
use App\Core\IO\Uploader\FileUploader;
use App\Form\EUPUBConfigType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eup", name="eup.")
 */
class EUPController extends AbstractBaseController
{

    /**
     * @Route("/{key}/ultimate-backup-rendering", name="rendering", methods={"GET"}, requirements={"key"= "[a-zA-Z0-9]+"})
     */
    public function uploadIniFile(string $storageDirectory, string $key)
    {
        $tmpFile = new File($storageDirectory . '/' . $key . '.tmp');
        $xmlFile = new File($storageDirectory . '/' . $key . '.xml');
        $isProcessed = $tmpFile->fileExists() && $xmlFile->fileExists();
        $xml = '';

        if ($isProcessed) {
            $xml = $xmlFile->getContent();
        }

        return $this->render('eup/rendering.html.twig', [
            'isProcessed' => $tmpFile->fileExists() && $xmlFile->fileExists(),
            'key' => $key,
            'xml' => $xml,
        ]);
    }

    /**
     * @Route("/upload", name="upload", methods={"POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function ubRendering(Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(EUPUBConfigType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $key = $fileUploader->uploadFile($form->getData()['ini_file']);
            return $this->redirectToRoute('eup.rendering', ['key' => $key]);
        }
        $this->addFlash('error', $this->getFormErrorsAsHTML($form->getErrors(true)));
        return $this->redirectToRoute('main.eup');
    }
}
