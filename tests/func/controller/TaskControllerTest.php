<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\TasktodoRepository;
use App\Repository\UsertodoRepository;

class TaskControllerTest extends WebTestCase
{

    public function testListAction()
    {

        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $client->request('GET', '/tasks');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testListDone()
    {

        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $client->request('GET', '/tasks/done');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $tasktodoRepo = static::$container->get(TasktodoRepository::class);

        $testTask = $tasktodoRepo->findOneByIsDone(true);

        $this->assertSame(200, $client->getResponse()->getStatusCode()); 
    }

    public function testTasksListPaginated()
    {

        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $client->request('GET', '/tasks?start=10&limit=10');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $tasktodoRepo = static::$container->get(TasktodoRepository::class);

        $testTask = $tasktodoRepo->findOneByIsDone(false);

        $this->assertSame(200, $client->getResponse()->getStatusCode()); 
    }

    public function testTasksListDonePaginated()
    {

        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $client->request('GET', '/tasks/done?start=1&limit=5');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $tasktodoRepo = static::$container->get(TasktodoRepository::class);

        $testTask = $tasktodoRepo->findOneByIsDone(true);

        $this->assertSame(200, $client->getResponse()->getStatusCode()); 
    }

    public function testErrorForTooManyTasksRequested()
    {

        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $client->request('GET', '/tasks/done?start=1&limit=101');

        $this->assertSame(500, $client->getResponse()->getStatusCode());

    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/tasks/create');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame("Créer une nouvelle tâche", $crawler->filter('h1')->text());

        $this->assertSame(1, $crawler->filter('input[name="tasktodo[title]"]')->count());

        $this->assertSame(1, $crawler->filter('textarea[name="tasktodo[content]"]')->count());

        $this->assertSame(1, $crawler->filter('button[type="submit"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['tasktodo[title]'] = 'Nouvelle tâche';

        $form['tasktodo[content]'] = 'Description de la tâche';

        $client->submit($form); 

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/tasks/1/edit');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="tasktodo[title]"]')->count());

        $this->assertSame(1, $crawler->filter('textarea[name="tasktodo[content]"]')->count());

        $this->assertSame(1, $crawler->filter('button[type="submit"]')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['tasktodo[title]'] = 'Titre de la tâche modifié';

        $form['tasktodo[content]'] = 'Description de la tâche modifiée';

        $client->submit($form);

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAction()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/tasks/1/toggle');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    public function testDeleteTaskActionBySimpleUser()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('nicolas');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/tasks/4/delete');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    public function testDeleteTaskByManager()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $usertodoRepo = static::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $client->loginUser($testManager);

        $crawler = $client->request('GET', '/tasks/2/delete');

        $this->assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    
}
