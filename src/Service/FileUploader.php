<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    /**
     * @param SluggerInterface $slugger
     * @param string $targetDirectory
     */
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly string $targetDirectory,
    ) {
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $targetDir = $this->getTargetDirectory();
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $file->move($targetDir, $fileName);
        } catch (FileException $e) {
            throw new \RuntimeException('Erro ao fazer upload da imagem: ' . $e->getMessage(), 0, $e);
        }

        return $fileName;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
