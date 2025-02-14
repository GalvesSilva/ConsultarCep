<?php

namespace App\Service;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ViaCepService
{
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private CacheInterface $cache;

    public function __construct(
        HttpClientInterface $httpClient, 
        EntityManagerInterface $entityManager, 
        LoggerInterface $logger,  
        CacheInterface $cache
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->cache = $cache;
    }

    public function getAddressByCep(string $cep): ?Address
    {
        return $this->cache->get("address_{$cep}", function (ItemInterface $item) use ($cep) {
            $item->expiresAfter(3600);

            try {
                $response = $this->httpClient->request('GET', "https://viacep.com.br/ws/{$cep}/json/");

                if ($response->getStatusCode() !== 200) {
                    return null;
                }

                $data = $response->toArray();

                if (isset($data['erro'])) {
                    return null;
                }

                return $this->saveAddress($cep, $data);
            } catch (\Exception $e) {
                $this->logger->error("Erro ao buscar CEP {$cep}: " . $e->getMessage());
                return null;
            }
        });
    }

    private function saveAddress(string $cep, array $data): Address
    {
        $address = $this->entityManager->getRepository(Address::class)->find($cep);
        if (!$address) {
            $address = new Address();
            $address->setCep($cep);
        }

        $address->setLogradouro($data['logradouro']);
        $address->setComplemento($data['complemento']);
        $address->setBairro($data['bairro']);
        $address->setLocalidade($data['localidade']);
        $address->setUf($data['uf']);
        $address->setIbge($data['ibge']);
        $address->setGia($data['gia']);
        $address->setDdd($data['ddd']);
        $address->setSiafi($data['siafi']);

        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }
}
