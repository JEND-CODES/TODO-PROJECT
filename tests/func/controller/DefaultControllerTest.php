<?php

// Pour les tests de coverage html voir la doc : https://phpunit.readthedocs.io/fr/latest/textui.html
// Installer https://xdebug.org/ ?

// Lancer " php bin/phpunit --help " pour voir toutes les options phpunit

// POUR GÉNÉRER LES RAPPORTS DE COUVERTURE HTML :
// Lancer " php bin/phpunit --coverage-html /[path where to save report] "

// Lancer par exemple pour un seul fichier : " php bin/phpunit --coverage-html nom-du-repertoire-créé tests/func/DefaultControllerTest.php "
// Lancer par exemple pour un seul fichier : " php bin/phpunit --coverage-html tests-coverage tests/func/DefaultControllerTest.php "

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
