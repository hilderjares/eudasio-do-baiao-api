<?php


namespace App\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class IndexController
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $response->getBody()->write("<h1> Eudásio do Baião API </h1>");
        return $response;
    }
}