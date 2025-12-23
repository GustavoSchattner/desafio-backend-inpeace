<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FileUploaderTest extends TestCase
{
    private string $targetDir;
    private string $tempFile;

    protected function setUp(): void
    {
        $this->targetDir = sys_get_temp_dir() . '/inpeace_test_' . uniqid();
        $this->ensureDirectoryDoesNotExist($this->targetDir);

        $this->tempFile = sys_get_temp_dir() . '/test_upload.jpg';
        file_put_contents($this->tempFile, "\xFF\xD8\xFF");
    }

    public function testUploadWorkflow(): void
    {
        $uploader = new FileUploader($this->targetDir, new AsciiSlugger());
        $file = $this->createMockUploadedFile('avatar_perfil.jpg');

        $filename = $uploader->upload($file);
        
        $this->assertDirectoryExists($this->targetDir);
        $this->assertFileExists($this->targetDir . '/' . $filename);

        $this->assertMatchesRegularExpression(
            '/^avatar-perfil-[a-z0-9]{13}\.(jpg|jpeg)$/', 
            $filename,
            "Padrão de nomeação incorreto."
        );
    }

    public function testDirectoryAutoCreation(): void
    {
        $uploader = new FileUploader($this->targetDir, new AsciiSlugger());
        $file = $this->createMockUploadedFile('avatar.jpg');

        $this->ensureDirectoryDoesNotExist($this->targetDir);

        $uploader->upload($file);

        $this->assertDirectoryExists($this->targetDir);
        
        $this->assertEquals(
            0755, 
            fileperms($this->targetDir) & 0777, 
            'Permissões do diretório inseguras.'
        );
    }

    public function testGetTargetDirectory(): void
    {
        $uploader = new FileUploader($this->targetDir, new AsciiSlugger());
        
        $this->assertEquals(
            $this->targetDir, 
            $uploader->getTargetDirectory(),
            'O getter deveria retornar o diretório configurado.'
        );
    }

    protected function tearDown(): void
    {
        $this->ensureDirectoryDoesNotExist($this->targetDir);
        
        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    private function createMockUploadedFile(string $originalName): UploadedFile
    {
        return new UploadedFile(
            $this->tempFile,
            $originalName,
            'image/jpeg',
            null,
            true
        );
    }

    private function ensureDirectoryDoesNotExist(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->ensureDirectoryDoesNotExist("$dir/$file") : unlink("$dir/$file");
        }
        rmdir($dir);
    }
}