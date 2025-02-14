<?php

namespace App\Tests\Service;

use App\Entity\Address;
use App\Service\ViaCepService;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ViaCepServiceTest extends TestCase
{
    private $httpClient;
    private $entityManager;
    private $logger;
    private $cache;
    private $viaCepService;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);

        $this->viaCepService = new ViaCepService(
            $this->httpClient,
            $this->entityManager,
            $this->logger,
            $this->cache
        );
    }

    public function testGetAddressByCepWithValidCep()
    {
        $cep = '12345678';
        $responseData = [
            'cep' => '12345678',
            'logradouro' => 'Rua Teste',
            'complemento' => '',
            'bairro' => 'Bairro Teste',
            'localidade' => 'Cidade Teste',
            'uf' => 'TS',
            'ibge' => '1234567',
            'gia' => '1234',
            'ddd' => '11',
            'siafi' => '1234',
        ];

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn($responseData);

        $this->httpClient->method('request')
            ->with('GET', "https://viacep.com.br/ws/{$cep}/json/")
            ->willReturn($response);

        $this->cache->method('get')
            ->willReturnCallback(function ($key, $callback) {
                return $callback($this->createMock(ItemInterface::class));
            });

        $addressRepository = $this->createMock(AddressRepository::class);
        $this->entityManager->method('getRepository')
            ->with(Address::class)
            ->willReturn($addressRepository);

        $address = $this->viaCepService->getAddressByCep($cep);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($cep, $address->getCep());
        $this->assertEquals('Rua Teste', $address->getLogradouro());
    }

    public function testGetAddressByCepWithInvalidCep()
    {
        $cep = '00000000';

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn(['erro' => true]);

        $this->httpClient->method('request')
            ->with('GET', "https://viacep.com.br/ws/{$cep}/json/")
            ->willReturn($response);

        $this->cache->method('get')
            ->willReturnCallback(function ($key, $callback) {
                return $callback($this->createMock(ItemInterface::class));
            });

        $address = $this->viaCepService->getAddressByCep($cep);

        $this->assertNull($address);
    }

    public function testGetAddressByCepWithHttpError()
    {
        $cep = '12345678';

        $this->httpClient->method('request')
            ->with('GET', "https://viacep.com.br/ws/{$cep}/json/")
            ->willThrowException(new \Exception('Erro na requisição HTTP'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with($this->stringContains("Erro ao buscar CEP {$cep}"));

        $this->cache->method('get')
            ->willReturnCallback(function ($key, $callback) {
                return $callback($this->createMock(ItemInterface::class));
            });

        $address = $this->viaCepService->getAddressByCep($cep);

        $this->assertNull($address);
    }

    public function testGetAddressByCepWithCache()
    {
        $cep = '12345678';
        $cachedAddress = new Address();
        $cachedAddress->setCep($cep);

        $this->cache->method('get')
            ->with("address_{$cep}")
            ->willReturn($cachedAddress);

        $this->httpClient->expects($this->never())
            ->method('request');

        $address = $this->viaCepService->getAddressByCep($cep);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals($cep, $address->getCep());
    }
}
