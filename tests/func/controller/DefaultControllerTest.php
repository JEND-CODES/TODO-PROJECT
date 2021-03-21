<?php

// Pour les tests de coverage html voir la doc : https://phpunit.readthedocs.io/fr/latest/textui.html
// Installer https://xdebug.org/ ?

// Lancer " php bin/phpunit --help " pour voir toutes les options phpunit

// POUR GÉNÉRER LES RAPPORTS DE COUVERTURE HTML :
// Lancer " php bin/phpunit --coverage-html /[path where to save report] "

// Lancer par exemple pour un seul fichier : " php bin/phpunit --coverage-html nom-du-repertoire-créé tests/func/DefaultControllerTest.php "
// Lancer par exemple pour un seul fichier : " php bin/phpunit --coverage-html tests-coverage tests/func/DefaultControllerTest.php "

// CRÉER UN DOSSIER ENTIER DE TOUS LES TESTS : " php bin/phpunit --coverage-html tests-coverage "

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testHomepage()
    {
        
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());

        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }
    

}
