<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
    private string $path = '/member/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndexRendersPagination(): void
    {
        $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Listagem de Membros');

        self::assertSelectorExists('table.table', 'A tabela de membros deve existir');

        self::assertAnySelectorTextContains('div', 'Total de membros');
    }

    public function testNewButtonExists(): void
    {
        $this->client->request('GET', $this->path);
        self::assertSelectorExists('a[href="/member/new"]');
    }
}
