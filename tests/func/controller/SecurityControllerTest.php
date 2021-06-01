<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UsertodoRepository;

class SecurityControllerTest extends WebTestCase
{

    public function testLoginHome()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/');
        
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

    public function testLoginManager()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/users');
        
        $this->assertResponseIsSuccessful();

        $this->assertSame(1, $crawler->filter('html:contains("Liste des utilisateurs")')->count());

    }
    
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());

        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $this->assertSame(1, $crawler->filter('html:contains("Se connecter")')->count());

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'pseudo';

        $form['_password'] = 'testtest';
        
        $client->submit($form); 

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame("TO DO LIST APP", $crawler->filter('a')->text());

        $this->assertSelectorExists('a', 'Se déconnecter');

    } 
    
    public function testLogout()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();

        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());

        $crawler = $client->request('GET', '/logout');

        $this->assertResponseRedirects();

        $this->assertSame(0, $crawler->filter('html:contains("Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !")')->count());

    }

    
}
