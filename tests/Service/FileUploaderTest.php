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

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->targetDir = sys_get_temp_dir() . '/inpeace_test_' . uniqid();
        $this->ensureDirectoryDoesNotExist($this->targetDir);

        $this->tempFile = sys_get_temp_dir() . '/test_upload.jpg';
        file_put_contents($this->tempFile, "\xFF\xD8\xFF");
    }

    /**
     * @return void
     */
    public function testDirectoryAutoCreation(): void
    {
        $uploader = new FileUploader(new AsciiSlugger(), $this->targetDir);
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

    /**
     * @return void
     */
    public function testGetTargetDirectory(): void
    {
        $uploader = new FileUploader(new AsciiSlugger(), $this->targetDir);

        $this->assertEquals(
            $this->targetDir,
            $uploader->getTargetDirectory(),
            'O getter deveria retornar o diretório configurado.'
        );
    }

    /**
     * @return void
     */
    public function testUploadWorkflow(): void
    {
        $uploader = new FileUploader(new AsciiSlugger(), $this->targetDir);
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

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->ensureDirectoryDoesNotExist($this->targetDir);

        if (file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    /**
     * @param string $originalName
     * @return UploadedFile
     */
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

    /**
     * @param string $dir
     * @return void
     */
    private function ensureDirectoryDoesNotExist(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            (is_dir($path)) ? $this->ensureDirectoryDoesNotExist($path) : unlink($path);
        }
        rmdir($dir);
    }
}