<?php

namespace App\Controller;

use App\Service\ViaCepService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AddressRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ViaCepController extends AbstractController
{
    private ViaCepService $viaCepService;

    public function __construct(ViaCepService $viaCepService)
    {
        $this->viaCepService = $viaCepService;
    }

    #[Route('/cep/{cep}', name: 'get_cep', methods: ['GET'])]
    public function getCep(string $cep): JsonResponse
    {
        if (!preg_match('/^\d{8}$/', $cep)) {
            return $this->json(['error' => 'Formato de CEP inválido'], 400);
        }

        $address = $this->viaCepService->getAddressByCep($cep);

        if ($address === null) {
            return $this->json(['error' => 'CEP não encontrado'], 404);
        }

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $json = $serializer->serialize($address, 'json');

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/cep', name: 'get_all_ceps', methods: ['GET'])]
    public function getAllCeps(
        AddressRepository $addressRepository,
        PaginatorInterface $paginator,
        Request $request
    ): JsonResponse {
        $queryBuilder = $addressRepository->createQueryBuilder('a');

        if ($request->query->get('cep')) {
            $queryBuilder->andWhere('a.cep LIKE :cep')
                         ->setParameter('cep', '%' . $request->query->get('cep') . '%');
        }
    
        $orderBy = $request->query->get('order_by', 'localidade'); 
        $orderDirection = strtoupper($request->query->get('order_direction', 'ASC')); 
    
        $validFields = ['localidade', 'bairro', 'uf'];
        if (in_array($orderBy, $validFields) && in_array($orderDirection, ['ASC', 'DESC'])) {
            $queryBuilder->orderBy('a.' . $orderBy, $orderDirection);
        } else {
            $queryBuilder->orderBy('a.localidade', 'ASC');
        }
    
        $query = $queryBuilder->getQuery();

        $page = $request->query->getInt('page', 1); 
        $limit = $request->query->getInt('limit', 10); 
        $pagination = $paginator->paginate($query, $page, $limit);

        return $this->json([
            'data' => $pagination->getItems(),
            'pagination' => [
                'current_page' => $pagination->getCurrentPageNumber(),
                'total_pages' => ceil($pagination->getTotalItemCount() / $limit),
                'total_items' => $pagination->getTotalItemCount(),
                'items_per_page' => $limit,
            ],
        ]);
    }
}
