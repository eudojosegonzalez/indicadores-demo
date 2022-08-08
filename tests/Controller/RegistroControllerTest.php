<?php

namespace App\Test\Controller;

use App\Entity\Registro;
use App\Repository\RegistroRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistroControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RegistroRepository $repository;
    private string $path = '/registro/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Registro::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Registro index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'registro[valor]' => 'Testing',
            'registro[ente]' => 'Testing',
            'registro[categoria]' => 'Testing',
            'registro[indicador]' => 'Testing',
            'registro[periodo]' => 'Testing',
        ]);

        self::assertResponseRedirects('/registro/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Registro();
        $fixture->setValor('My Title');
        $fixture->setEnte('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setIndicador('My Title');
        $fixture->setPeriodo('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Registro');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Registro();
        $fixture->setValor('My Title');
        $fixture->setEnte('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setIndicador('My Title');
        $fixture->setPeriodo('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'registro[valor]' => 'Something New',
            'registro[ente]' => 'Something New',
            'registro[categoria]' => 'Something New',
            'registro[indicador]' => 'Something New',
            'registro[periodo]' => 'Something New',
        ]);

        self::assertResponseRedirects('/registro/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getValor());
        self::assertSame('Something New', $fixture[0]->getEnte());
        self::assertSame('Something New', $fixture[0]->getCategoria());
        self::assertSame('Something New', $fixture[0]->getIndicador());
        self::assertSame('Something New', $fixture[0]->getPeriodo());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Registro();
        $fixture->setValor('My Title');
        $fixture->setEnte('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setIndicador('My Title');
        $fixture->setPeriodo('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/registro/');
    }
}
