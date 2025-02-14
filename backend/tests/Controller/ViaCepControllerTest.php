<?php

namespace App\Tests\Controller;

use App\Controller\ViaCepController;
use App\Service\ViaCepService;
use App\Entity\Address;
use App\Repository\AddressRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViaCepControllerTest extends KernelTestCase
{
    private $viaCepService;
    private $addressRepository;
    private $paginator;
    private $controller;

    protected function setUp(): void
    {
        self::bootKernel(); 
        $container = self::getContainer();

        $this->viaCepService = $this->createMock(ViaCepService::class);
        $this->addressRepository = $this->createMock(AddressRepository::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);

        $this->controller = new ViaCepController($this->viaCepService);
        $this->controller->setContainer($container); 
    }

    public function testGetCepWithValidCep()
    {
        $cep = '12345678';
        $address = new Address();
        $address->setCep($cep);
        $address->setLogradouro('Rua Teste');

        $this->viaCepService->method('getAddressByCep')
            ->with($cep)
            ->willReturn($address);

        $response = $this->controller->getCep($cep);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedJson = json_encode([
            'cep' => '12345678',
            'logradouro' => 'Rua Teste',
            'complemento' => null,
            'bairro' => null,
            'localidade' => null,
            'uf' => null,
            'ibge' => null,
            'gia' => null,
            'ddd' => null,
            'siafi' => null,
        ]);

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }

    public function testGetCepWithInvalidCep()
    {
        $cep = '1234567';

        $response = $this->controller->getCep($cep);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Formato de CEP inválido']), $response->getContent());
    }

    public function testGetCepWithNotFoundCep()
    {
        $cep = '12345678';

        $this->viaCepService->method('getAddressByCep')
            ->with($cep)
            ->willReturn(null);

        $response = $this->controller->getCep($cep);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'CEP não encontrado']), $response->getContent());
    }

    public function testGetAllCeps()
    {
        $request = new Request([
            'page' => 1,
            'limit' => 10,
            'order_by' => 'localidade',
            'order_direction' => 'ASC'
        ]);

        $expectedItems = [
            ['cep' => '12345678', 'localidade' => 'Cidade Teste'],
            ['cep' => '87654321', 'localidade' => 'Outra Cidade']
        ];

        $pagination = $this->createMock(PaginationInterface::class);
        $pagination->method('getItems')->willReturn($expectedItems);
        $pagination->method('getCurrentPageNumber')->willReturn(1);
        $pagination->method('getTotalItemCount')->willReturn(2);

        $this->paginator->method('paginate')
            ->willReturn($pagination);

        $response = $this->controller->getAllCeps($this->addressRepository, $this->paginator, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedResponse = [
            'data' => $expectedItems,
            'pagination' => [
                'current_page' => 1,
                'total_pages' => 1,
                'total_items' => 2,
                'items_per_page' => 10,
            ],
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), $response->getContent());
    }
}
