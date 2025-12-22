<?php

namespace App\Repository;

use App\Entity\Church;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByChurch(Church $church): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.church = :church')
            ->setParameter('church', $church)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCpf(string $cpf): ?Member
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.cpf = :cpf')
            ->setParameter('cpf', $cpf)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByEmail(string $email): ?Member
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findWithChurch(): array
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.church', 'c')
            ->addSelect('c')
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByChurch(Church $church): int
    {
        return (int) $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->andWhere('m.church = :church')
            ->setParameter('church', $church)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByCity(string $city): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.city = :city')
            ->setParameter('city', $city)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
