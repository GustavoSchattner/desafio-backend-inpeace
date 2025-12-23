<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Church;
use App\Repository\ChurchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChurchRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        
        $container = self::getContainer();
        /** @var ManagerRegistry $registry */
        $registry = $container->get('doctrine');
        
        $manager = $registry->getManager();

        if (!$manager instanceof EntityManagerInterface) {
            throw new \RuntimeException('EntityManager não compatível.');
        }
        
        $this->entityManager = $manager;
    }

    public function testSaveAndFindByNameAndCount(): void
    {
        $this->assertNotNull($this->entityManager);

        /** @var ChurchRepository $repo */
        $repo = $this->entityManager->getRepository(Church::class);

        $church = new Church();
        $church->setName('Unit Test Church');
        $church->setAddress('Test City');
        
        $this->entityManager->persist($church);
        $this->entityManager->flush();

        $result = $repo->findByName('Unit Test');
        
        $this->assertNotEmpty($result); 
        $this->assertSame('Unit Test Church', $result[0]->getName());

        $count = $repo->countChurches();
        $this->assertGreaterThanOrEqual(1, $count);

        $recent = $repo->findRecentChurches(5);
        $this->assertNotEmpty($recent); 
    }

    public function testFindByAddressCityAndPaginationQuery(): void
    {
        $this->assertNotNull($this->entityManager);

        /** @var ChurchRepository $repo */
        $repo = $this->entityManager->getRepository(Church::class);

        $church = new Church();
        $church->setName('Busca Igreja');
        $church->setAddress('Cidade Busca');
        $this->entityManager->persist($church);
        $this->entityManager->flush();

        $res = $repo->findByAddressCity('Cidade Busca');
        $this->assertCount(1, $res); 

        $q = $repo->getPaginationQuery();
        $this->assertNotEmpty($q->getDQL());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->entityManager) {
            $this->entityManager->close();
        }
        $this->entityManager = null;
    }
}