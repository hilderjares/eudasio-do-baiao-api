<?php


namespace App\Controller;

use App\Entity\Item;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class ItemController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response, $args)
    {
        $entityManager = $this->container->get('em');

        $items = $entityManager->getRepository('App\Entity\Item')
            ->findAll();

        $data = [];
        foreach ($items as $item) {
            $data[] = $item->jsonSerialize();
        }

        $newResponse = $response->withJson($data, 200)->withHeader('Content-type', 'application/json');
        return $newResponse;
    }

    public function create(Request $request, Response $response, $args)
    {
        $params = (object)$request->getParams();
        $entityManager = $this->container->get('em');

        $item = new Item();
        $item->setName($params->name);
        $item->setDescription($params->description);
        $item->setPrice($params->price);

        $entityManager->persist($item);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => 'prato criado com sucesso'], 201);
        return $newResponse;
    }

    public function update(Request $request, Response $response, $args)
    {
        $id = (int)$args['id'];
        $params = (object)$request->getParams();

        $entityManager = $this->container->get('em');

        $item = $entityManager->getRepository('App\Entity\Item')
            ->find($id);

        if (!$item) {
            $newResponse = $response->withJson(['message' => "não existem item com o id: {$id}"], 404);
            return $newResponse;
        }

        $item->setName($params->name);
        $item->setDescription($params->description);
        $item->setPrice($params->price);

        $entityManager->persist($item);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => "atualizado o item com o id: {$id}"], 200);
        return $newResponse;
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = (int)$args['id'];

        $entityManager = $this->container->get('em');

        $item = $entityManager->getRepository('App\Entity\Item')
            ->find($id);

        if (!$item) {
            $newResponse = $response->withJson(['message' => "não existem item com o id: {$id}"], 404);
            return $newResponse;
        }

        $entityManager->remove($item);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => "deletado item com o id: {$id}"], 200);
        return $newResponse;
    }
}