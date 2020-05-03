<?php

namespace App\Controller;

use App\Form\EUPUBConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="main.")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [

        ]);
    }

    /**
     * @Route("/eup", name="eup", methods={"GET"})
     */
    public function eup()
    {
        $form = $this->createForm(EUPUBConfigType::class, null);
        return $this->render('main/eup.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

}
