<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UsertodoRepository;

class SecurityControllerTest extends WebTestCase
{

    public function testLoginManager()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');
        // $testUser = $usertodoRepo->findOneByUsername('billy');

        $client->loginUser($testManager);

        $client->request('GET', '/users');
        
        $this->assertResponseIsSuccessful();
    }
    
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="_username"]')->count());

        $this->assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'user';

        $form['_password'] = 'test';

        $client->submit($form); 

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame("TO DO LIST APP", $crawler->filter('a')->text());
       
    }
    
    public function testRedirect()
    {
        $client = static::createClient();

        $client->request('GET', '/logout');

        $this->assertResponseRedirects();
    }

    

}
