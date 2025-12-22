<?php

namespace App\Service;

use App\Entity\Church;
use Doctrine\ORM\EntityManagerInterface;

class ChurchManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function deleteChurch(Church $church, string $action): void
    {
        $this->entityManager->beginTransaction();

        try {
            if ($action === 'cascade') {
                foreach ($church->getMembers() as $member) {
                    $this->entityManager->remove($member);
                }
            } elseif ($action === 'orphans') {
                foreach ($church->getMembers() as $member) {
                    $member->setChurch(null);
                }
            } else {
                throw new \InvalidArgumentException('Ação de deleção inválida.');
            }

            $this->entityManager->remove($church);

            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
