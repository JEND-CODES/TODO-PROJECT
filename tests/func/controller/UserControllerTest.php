<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use App\Repository\TasktodoRepository;
use App\Repository\UsertodoRepository;

class UserControllerTest extends WebTestCase
{

    public function testListAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/users');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());

        $this->assertSame("Nom d'utilisateur", $crawler->filter('th')->text());

    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/users/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Ajouter')->form();

        $form['usertodo[username]'] = 'michel';

        $form['usertodo[email]'] = 'michel@test.com';

        $form['usertodo[password][first]'] = 'testtest';

        $form['usertodo[password][second]'] = 'testtest';

        $form['usertodo[role]'] = 'ROLE_USER';

        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    public function testEditAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/users/4/edit');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="usertodo[username]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['usertodo[username]'] = 'nicolas';

        $form['usertodo[password][first]'] = 'testtest';

        $form['usertodo[password][second]'] = 'testtest';

        $form['usertodo[email]'] = 'nicolas@gmail.com';

        $form['usertodo[role]'] = 'ROLE_USER';

        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    }

    public function testEditActionWithDeniedAccess()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testAdmin = $usertodoRepo->findOneByUsername('paolo');

        $client->loginUser($testAdmin);

        $crawler = $client->request('GET', '/users/3/edit');

        $this->assertSame(404, $client->getResponse()->getStatusCode());

    }

    public function testDeleteAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/users/3/delete');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

   /*  public function testDeleteActionWithDeniedAccess()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testAdmin = $usertodoRepo->findOneByUsername('paolo');

        $client->loginUser($testAdmin);

        $crawler = $client->request('GET', '/users/3/delete');

        $this->assertSame(404, $client->getResponse()->getStatusCode());

    } */
    
}
