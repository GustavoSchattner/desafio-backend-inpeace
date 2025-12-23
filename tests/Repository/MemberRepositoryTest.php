<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Member;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MemberRepositoryTest extends KernelTestCase
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
            throw new \RuntimeException('EntityManager não compatível ou não encontrado.');
        }
        
        $this->entityManager = $manager;
    }

    public function testFindByNameAndByCpfAndEmail(): void
    {
        $this->assertNotNull($this->entityManager);

        $member = new Member();
        $member->setName('Teste Repository');
        $member->setCpf('12345678900');
        $member->setEmail('teste@repo.com');
        $member->setBirthDate(new \DateTime('1990-01-01'));
        $member->setPhone('27999999999');
        $member->setAddress('Rua dos Testes, 123');
        $member->setCity('São Mateus');
        $member->setState('ES');

        $this->entityManager->persist($member);
        $this->entityManager->flush();

        /** @var MemberRepository $repo */
        $repo = $this->entityManager->getRepository(Member::class);

        $found = $repo->findOneBy(['cpf' => '12345678900']);

        $this->assertNotNull($found);
        $this->assertSame('Teste Repository', $found->getName());
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