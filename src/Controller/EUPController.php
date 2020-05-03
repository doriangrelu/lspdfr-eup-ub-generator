<?php

namespace App\Controller;

use App\Form\EUPUBConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eup", name="eup.")
 */
class EUPController extends AbstractController
{

    /**
     * @Route("/{key}/ultimate-backup-rendering", name="rendering", methods={"GET"})
     */
    public function uploadIniFile()
    {

    }

    /**
     * @Route("/upload", name="upload", methods={"POST"})
     */
    public function ubRendering(Request $request): Response
    {
        $form = $this->createForm(EUPUBConfigType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dd($form->getData()['ini_file']);
            dd('here');
        }

        dd($form->getErrors());

        $this->addFlash('error', $form->getErrors());
        return $this->redirectToRoute('main.eup');
    }
}
