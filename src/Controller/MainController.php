<?php

namespace App\Controller;

use App\Form\EUPUBConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main.")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [

        ]);
    }

    /**
     * @Route("/eup", name="eup", methods={"GET"})
     */
    public function eup(bool $isActiveEup): Response
    {
        $form = null;
        if ($isActiveEup) {
            $form = $this->createForm(EUPUBConfigType::class, null);
            $form = $form->createView();
        }
        return $this->render('main/eup.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/github-repositories", name="repos", methods={"GET"})
     */
    public function repositories(): Response
    {
        return $this->render('main/repos.html.twig', [
            'repos' => [
                'Gestionnaire EUP & Site actuel' => 'https://github.com/doriangrelu/lspdfr-eup-ub-generator',
            ]
        ]);
    }

}
