<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;

abstract class AbstractBaseController extends AbstractController
{


    protected function getFormErrorsAsHTML(FormErrorIterator $errors): string
    {
        $rendering = 'Des erreurs sont présentes dans le formulaire: <br><ul>';
        foreach ($errors as $error) {
            $rendering .= "<li>{$error->getMessage()}</li> ";
        }
        return $rendering . '<ul>';
    }

}