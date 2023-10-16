<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function handleError(\Throwable $exception): Response
    {   dd($exception);
        if (method_exists($exception, 'getStatusCode') && $exception->getStatusCode() === 404){
            return $this->render('error/error404.html.twig', []);
        } elseif (method_exists($exception, 'getStatusCode')) {
            return $this->render('error/error.html.twig', [
                'exception_message' => 'Une erreur s\'est produite',
            ]);
        } else {
            dd($exception);
            return $this->render('error/error.html.twig', [
                'exception_message' => 'Une erreur s\'est produite',
            ]);
        }
    }
}