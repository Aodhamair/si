<?php

/**
 * Main Controller
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package App\Controller
 */
class MainController extends AbstractController
{
    /**
     * Index
     * @Route("",name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}
