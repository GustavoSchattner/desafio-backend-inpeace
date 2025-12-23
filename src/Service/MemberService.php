<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;

class MemberService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param Member $member
     * @return void
     */
    public function save(Member $member): void
    {
        $this->entityManager->persist($member);
        $this->entityManager->flush();
    }

    /**
     * @param Member $member
     * @return void
     */
    public function remove(Member $member): void
    {
        $this->entityManager->remove($member);
        $this->entityManager->flush();
    }
}
