<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Church;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChurchRepositoryTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = self::getContainer()->get('doctrine')->getManager();
    }

    public function testSaveAndFindByNameAndCount(): void
    {
        $repo = $this->em->getRepository(Church::class);

        $church = new Church();
        $church->setName('Unit Test Church');
        $church->setAddress('Test City');
        $this->em->persist($church);
        $this->em->flush();

        $result = $repo->findByName('Unit Test');
        $this->assertNotEmpty($result);
        $this->assertSame('Unit Test Church', $result[0]->getName());

        $count = $repo->countChurches();
        $this->assertGreaterThanOrEqual(1, $count);

        $recent = $repo->findRecentChurches(5);
        $this->assertIsArray($recent);
    }

    public function testFindByAddressCityAndPaginationQuery(): void
    {
        $repo = $this->em->getRepository(Church::class);

        $res = $repo->findByAddressCity('Test City');
        $this->assertIsArray($res);

        $q = $repo->getPaginationQuery();
        $this->assertNotNull($q);
    }
}
