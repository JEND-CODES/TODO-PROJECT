<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * TEST DE REDIRECTION VERS LA PAGE LOGIN (UTILISATEUR NON AUTHENTIFIÉ)
     */
    public function testIndexAction()
    {
        $this->client->request('GET', '/');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSelectorExists('form');

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());

        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }

    /**
     * AUTRE FORMAT DE TEST DE REDIRECTION
     */
    public function testRedirect()
	{
		$this->client->request('GET', '/');

		$this->client->followRedirect();

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());

	}

    /**
     * TEST DE PAGE ERRONÉE
     */
    public function testErrorPage()
    {
        $this->client->request('GET', '/azerty');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

}
