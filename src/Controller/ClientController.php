<?php


namespace App\Controller;


use App\Entity\Client;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Entity\Request as Solicitation;

final class ClientController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response, $args)
    {
        $entityManager = $this->container->get('em');

        $clients = $entityManager->getRepository('App\Entity\Client')
            ->findAll();

        $data = [];
        foreach ($clients as $client) {
            $data[] = $client->jsonSerialize();
        }

        $newResponse = $response->withJson($data, 200)->withHeader('Content-type', 'application/json');
        return $newResponse;
    }

    public function create(Request $request, Response $response, $args)
    {
        $params = (object)$request->getParams();
        $entityManager = $this->container->get('em');

        $client = new Client();
        $client->setName($params->name);
        $client->setAddress($params->address);
        $client->setBirthday($params->birthday);
        $client->setPhone($params->phone);

        $entityManager->persist($client);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => 'cliente criado com sucesso'], 201);
        return $newResponse;
    }

    public function show(Request $request, Response $response, $args)
    {
        $id = (int)$args['id'];
        $entityManager = $this->container->get('em');

        $query = $entityManager->createQuery('SELECT r FROM App\Entity\Request r WHERE r.client = :id');
        $query->setParameter('id', $id);

        $solicitations = $query->getResult();

        if (!$solicitations) {
            $newResponse = $response->withJson(['message' => 'cliente não tem nenhum pedido'], 404);
            return $newResponse;
        }

        $data = [];
        foreach ($solicitations as $solicitation) {
            $data[] = $solicitation->jsonSerialize();
        }

        $newResponse = $response->withJson($data, 200);
        return $newResponse;
    }

    public function buy(Request $request, Response $response, $args)
    {
        $id = (int)$args['id'];
        $entityManager = $this->container->get('em');

        $solicitation = $entityManager->getRepository('App\Entity\Request')->find($id);

        if (!$solicitation) {
            $newResponse = $response->withJson(['message' => 'cliente não tem nenhum pedido'], 404);
            return $newResponse;
        }

        $solicitation->setStatus(Solicitation::SOLICITATION_STATUS['done']);

        $entityManager->merge($solicitation);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => 'pedido finalizado'], 200);
        return $newResponse;
    }
}