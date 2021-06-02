<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UsertodoRepository;

class SecurityControllerTest extends WebTestCase
{

    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * TEST DE CONNEXION ET INSPECTIONS DE LA PAGE D'ACCUEIL
     */
    public function testLoginHome()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();

        $this->assertSelectorNotExists('form');

        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());

        $this->assertSelectorExists('a', 'Créer un utilisateur');

        $this->assertSelectorExists('a', 'Gérer les utilisateurs');

        $this->assertSelectorExists('a', 'Se déconnecter');

        $this->assertSelectorExists('a', 'Créer une tâche');

        $this->assertSelectorExists('a', 'Tâches à réaliser');

        $this->assertSelectorExists('a', 'Tâches terminées');

    }

    /**
     * TEST D'ACCÈS À LA PAGE DE GESTION DES UTILISATEURS PAR LE MANAGER
     */
    public function testLoginManager()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/users');
        
        $this->assertResponseIsSuccessful();

        $this->assertSame(1, $crawler->filter('html:contains("Liste des utilisateurs")')->count());

    }
    
    /**
     * TEST DU FORMULAIRE DE CONNEXION
     */
    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());

        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'pseudo';

        $form['_password'] = 'testtest';
        
        $this->client->submit($form); 

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame("TO DO LIST APP", $crawler->filter('a')->text());

        $this->assertSelectorExists('a', 'Se déconnecter');

    } 
    
    /**
     * TEST DE DÉCONNEXION
     */
    public function testLogout()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();

        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());

        $crawler = $this->client->request('GET', '/logout');

        $this->assertResponseRedirects();

        $this->assertSame(0, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());

    }

    
}
