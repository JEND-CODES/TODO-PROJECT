<?php
// LANCER LE LIVE ! php -S localhost:7000 -t public

// PROJET DE DÉPART SUR GITHUB : https://github.com/saro0h/projet8-TodoList

// Démo : https://github.com/sorha/P8-ToDoAndCo

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
