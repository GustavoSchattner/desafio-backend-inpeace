<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Church;
use App\Repository\ChurchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChurchService
{
    public const ACTION_CASCADE_DELETE = 'cascade_delete';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private FileUploader $fileUploader,
        private ChurchRepository $churchRepository,
    ) {
    }

    public function handleImageUpload(Church $church, ?UploadedFile $imageFile): void
    {
        if (!$imageFile) {
            return;
        }
        $newFilename = $this->fileUploader->upload($imageFile);
        $church->setImage($newFilename);
    }

    public function save(Church $church): void
    {
        $this->entityManager->persist($church);
        $this->entityManager->flush();
    }

    public function deleteWithAction(Church $church, ?string $action): string
    {
        $message = 'Igreja removida. Membros ficaram sem vínculo.';

        if (self::ACTION_CASCADE_DELETE === $action) {
            foreach ($church->getMembers() as $member) {
                $this->entityManager->remove($member);
            }
            $message = 'Igreja e todos os seus membros foram removidos.';

        } elseif ($action) {
            $targetChurch = $this->churchRepository->find($action);
            if ($targetChurch && $targetChurch->getId() !== $church->getId()) {
                foreach ($church->getMembers() as $member) {
                    $member->setChurch($targetChurch);
                }
                $message = 'Membros transferidos para '.$targetChurch->getName().'.';
            }

        } else {
            foreach ($church->getMembers() as $member) {
                $member->setChurch(null);
            }
        }

        $this->entityManager->remove($church);
        $this->entityManager->flush();

        return $message;
    }
}
