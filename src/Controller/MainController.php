<?php

namespace App\Controller;

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
        //parse_ini_file
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
