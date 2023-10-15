<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function handleError(\Throwable $exception): Response
    {
    $code = $exception->getStatusCode();

    if ($code === 404) {
        return $this->render('error/error404.html.twig', []);
    }

    return $this->render('error/error.html.twig', []);
    }
}