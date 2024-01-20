<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController extends AbstractController
{
    public function handleError(\Throwable $exception): Response
    {
        $statusCode = null;
        $exceptionMessage = 'Une erreur s\'est produite';
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();

            if ($statusCode === 404) {
                return $this->render('error/error404.html.twig', [
                    'exception' => $exception,
                ]);
            } else {
                return $this->render('error/error.html.twig', [
                    'statusCode' => $statusCode,
                    'exception' => $exception,
                    'exception_message' => $exceptionMessage,
                ]);
            }
        } else {
            // Handle other exceptions here.
            return $this->render('error/error.html.twig', [
                'statusCode' => $statusCode,
                'exception' => $exception,
                'exception_message' => $exceptionMessage,
            ]);
        }
    }
}
