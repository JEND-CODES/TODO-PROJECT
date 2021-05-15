<?php

// CRÉER UN DOSSIER ENTIER DE TOUS LES TESTS : " php bin/phpunit --coverage-html tests-coverage "

// LANCER LES FIXTURES ! " php bin/console doctrine:fixtures:load "

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testIndexAction()
    {

        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());

        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }
    
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        // Code 302 indique que l'on a trouvé la route après redirection
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

    }

    public function testRedirect()
	{
        $client = static::createClient();

		$crawler = $client->request('GET', '/');

		$client->followRedirect();

		$this->assertEquals(200, $client->getResponse()->getStatusCode());

	}

    public function testErrorPage()
    {
        $client = static::createClient();

        $client->request('GET', '/azerty');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

}
