<?php

namespace App\Controller;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Entity\Request as Solicitation;

final class RequestController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response, $args)
    {
        $entityManager = $this->container->get('em');

        $solicitations = $entityManager->getRepository('App\Entity\Request')
            ->findAll();

        $data = [];
        foreach ($solicitations as $solicitation) {
            $data[] = $solicitation->jsonSerialize();
        }

        $newResponse = $response->withJson($data, 200)->withHeader('Content-type', 'application/json');
        return $newResponse;
    }

    public function create(Request $request, Response $response, $args)
    {
        $entityManager = $this->container->get('em');

        $params = (object)$request->getParams();

        $client = $entityManager->getRepository('App\Entity\Client')->find($params->client);

        if (!$client) {
            $newResponse = $response->withJson(['message' => "não existe cliente com o id: {$params->client}"], 404);
            return $newResponse;
        }

        $itemsId = $params->items;
        $items = new ArrayCollection();
        $totalValue = 0.0;
        foreach ($itemsId as $id) {
            $item = $entityManager->getRepository('App\Entity\Item')->find($id);

            if (!$item) {
                $newResponse = $response->withJson(['message' => "não existem item com o id: {$id}"], 404);
                return $newResponse;
            }
            $totalValue += $item->getPrice();
            $items->add($item);
        }

        $solicitation = new Solicitation();
        $solicitation->setClient($client);
        $solicitation->setCreatedAt(new DateTime());
        $solicitation->setDeliveryAddress($params->address);
        $solicitation->setPaymentType($params->payment_type);
        $solicitation->setItems($items);
        $solicitation->setTotalValue($totalValue);
        $solicitation->setStatus((Solicitation::SOLICITATION_STATUS['default']));

        $entityManager->persist($solicitation);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => "pedido cridado"], 200);
        return $newResponse;
    }

    public function delete(Request $request, Response $response, $args)
    {
        $id = (int)$args['id'];

        $entityManager = $this->container->get('em');

        $solicitation = $entityManager->getRepository('App\Entity\Request')
            ->find($id);

        if (!$solicitation) {
            $newResponse = $response->withJson(['message' => "não existe pedido com o id: {$id}"], 404);
            return $newResponse;
        }

        $entityManager->remove($solicitation);
        $entityManager->flush();

        $newResponse = $response->withJson(['message' => "deletado o pedido com o id: {$id}"], 200);
        return $newResponse;
    }
}