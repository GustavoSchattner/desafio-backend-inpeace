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

    /**
     * @param ChurchRepository $churchRepository
     * @param EntityManagerInterface $entityManager
     * @param FileUploader $fileUploader
     */
    public function __construct(
        private ChurchRepository $churchRepository,
        private EntityManagerInterface $entityManager,
        private FileUploader $fileUploader,
    ) {
    }

    /**
     * @param Church $church
     * @param UploadedFile|null $imageFile
     * @return void
     */
    public function handleImageUpload(Church $church, ?UploadedFile $imageFile): void
    {
        if (!$imageFile) {
            return;
        }
        $newFilename = $this->fileUploader->upload($imageFile);
        $church->setImage($newFilename);
    }

    /**
     * @param Church $church
     * @return void
     */
    public function save(Church $church): void
    {
        $this->entityManager->persist($church);
        $this->entityManager->flush();
    }

    /**
     * @param Church $church
     * @param string|null $action
     * @return string
     */
    public function deleteWithAction(Church $church, ?string $action): string
    {
        if (self::ACTION_CASCADE_DELETE === $action) {
            return $this->handleCascadeDelete($church);
        }

        if ($action) {
            $result = $this->handleMemberTransfer($church, $action);
            if ($result !== null) {
                return $result;
            }
        }

        return $this->handleOrphanMembers($church);
    }

    /**
     * @param Church $church
     * @return string
     */
    private function handleCascadeDelete(Church $church): string
    {
        foreach ($church->getMembers() as $member) {
            $this->entityManager->remove($member);
        }
        $this->finalizeDeletion($church);
        return 'Igreja e todos os seus membros foram removidos.';
    }

    /**
     * @param Church $church
     * @param string $targetChurchId
     * @return string|null
     */
    private function handleMemberTransfer(Church $church, string $targetChurchId): ?string
    {
        $targetChurch = $this->churchRepository->find($targetChurchId);

        if (!$targetChurch || $targetChurch->getId() === $church->getId()) {
            return null;
        }

        foreach ($church->getMembers() as $member) {
            $member->setChurch($targetChurch);
        }
        $this->finalizeDeletion($church);
        return 'Membros transferidos para ' . $targetChurch->getName() . '.';
    }

    /**
     * @param Church $church
     * @return string
     */
    private function handleOrphanMembers(Church $church): string
    {
        foreach ($church->getMembers() as $member) {
            $member->setChurch(null);
        }
        $this->finalizeDeletion($church);
        return 'Igreja removida. Membros ficaram sem vínculo.';
    }

    /**
     * @param Church $church
     * @return void
     */
    private function finalizeDeletion(Church $church): void
    {
        $this->entityManager->remove($church);
        $this->entityManager->flush();
    }
}
