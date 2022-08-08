<?php

namespace App\Test\Controller;

use App\Entity\Indicador;
use App\Repository\IndicadorRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndicadorControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private IndicadorRepository $repository;
    private string $path = '/indicador/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Indicador::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Indicador index');

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
            'indicador[nombre]' => 'Testing',
            'indicador[formula]' => 'Testing',
            'indicador[ente]' => 'Testing',
            'indicador[categoria]' => 'Testing',
        ]);

        self::assertResponseRedirects('/indicador/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Indicador();
        $fixture->setNombre('My Title');
        $fixture->setFormula('My Title');
        $fixture->setEnte('My Title');
        $fixture->setCategoria('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Indicador');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Indicador();
        $fixture->setNombre('My Title');
        $fixture->setFormula('My Title');
        $fixture->setEnte('My Title');
        $fixture->setCategoria('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'indicador[nombre]' => 'Something New',
            'indicador[formula]' => 'Something New',
            'indicador[ente]' => 'Something New',
            'indicador[categoria]' => 'Something New',
        ]);

        self::assertResponseRedirects('/indicador/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNombre());
        self::assertSame('Something New', $fixture[0]->getFormula());
        self::assertSame('Something New', $fixture[0]->getEnte());
        self::assertSame('Something New', $fixture[0]->getCategoria());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Indicador();
        $fixture->setNombre('My Title');
        $fixture->setFormula('My Title');
        $fixture->setEnte('My Title');
        $fixture->setCategoria('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/indicador/');
    }
}
