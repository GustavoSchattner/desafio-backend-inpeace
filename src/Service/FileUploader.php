<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private readonly string $targetDirectory,
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $targetDir = $this->getTargetDirectory();
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $file->move($targetDir, $fileName);
        } catch (FileException $e) {
            throw new \RuntimeException('Erro ao fazer upload da imagem: '.$e->getMessage(), 0, $e);
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function testUploadCreatesTargetDirectoryIfItDoesNotExist(): void
{
    if ($this->filesystem->exists($this->targetDir)) {
        $this->filesystem->remove($this->targetDir);
    }
    
    $uploader = new FileUploader($this->targetDir, new AsciiSlugger());

    $uploader->upload($file);

    $this->assertDirectoryExists($this->targetDir, 'O diretório deveria ter sido criado automaticamente.');
}
}
