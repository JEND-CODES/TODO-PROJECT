<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\TasktodoRepository;
use App\Repository\UsertodoRepository;

class TaskControllerTest extends WebTestCase
{

    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * TEST D'ACCÈS À LA LISTE DES TÂCHES À RÉALISER
     */
    public function testListAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSelectorNotExists('.glyphicon-ok');
    }

    /**
     * TESTS D'ACCÈS AUX ROUTES PROTÉGÉES DE GESTIONS DES TÂCHES
     */
    public function testProtectedPathTasksAccess()
    {
        $paths = [
            ['GET', '/tasks'],
            ['GET', '/tasks/done'],
            ['GET', '/tasks/6/edit']
        ];

        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        foreach ($paths as $path) {

            $this->client->request($path[0], $path[1]);

            $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        }

    }

    /**
     * TEST D'ACCÈS À LA LISTE DES TÂCHES TERMINÉES
     */
    public function testListDone()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks/done');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $tasktodoRepo = self::$container->get(TasktodoRepository::class);

        $taskDone = $tasktodoRepo->findOneByIsDone(true);

        $this->assertNotNull($taskDone);

        $this->assertSelectorExists('.glyphicon-ok');

    }

    /**
     * TEST D'ACCÈS À LA LISTE PAGINÉE DES TÂCHES À RÉALISER
     */
    public function testTasksListPaginated()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks?start=10&limit=10');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSelectorNotExists('.glyphicon-ok');

    }

    /**
     * TEST D'ACCÈS À LA LISTE PAGINÉE DES TÂCHES TERMINÉES
     */
    public function testTasksListDonePaginated()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks/done?start=1&limit=5');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $tasktodoRepo = self::$container->get(TasktodoRepository::class);

        $taskNotDone = $tasktodoRepo->findOneByIsDone(true);

        $this->assertNotNull($taskNotDone);

        $this->assertSelectorExists('.glyphicon-ok');
        
    }

    /**
     * TEST D'ERREUR GÉNÉRÉE LIÉE À LA PAGINATION (TROP D'ITEMS DEMANDÉS)
     */
    public function testErrorForTooManyTasksRequested()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks/done?start=1&limit=101');

        $this->assertSame(500, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST DE CRÉATION D'UNE TÂCHE
     */
    public function testCreateAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/tasks/create');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame("Créer une nouvelle tâche", $crawler->filter('h1')->text());

        $this->assertSame(1, $crawler->filter('input[name="tasktodo[title]"]')->count());

        $this->assertSame(1, $crawler->filter('textarea[name="tasktodo[content]"]')->count());

        $this->assertSame(1, $crawler->filter('button[type="submit"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['tasktodo[title]'] = 'Nouvelle tâche';

        $form['tasktodo[content]'] = 'Description de la tâche';

        $this->client->submit($form); 

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * TEST DE MODIFICATION D'UNE TÂCHE
     */
    public function testEditAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/tasks/1/edit');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="tasktodo[title]"]')->count());

        $this->assertSame(1, $crawler->filter('textarea[name="tasktodo[content]"]')->count());

        $this->assertSame(1, $crawler->filter('button[type="submit"]')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['tasktodo[title]'] = 'Titre de la tâche modifié';

        $form['tasktodo[content]'] = 'Description de la tâche modifiée';

        $this->client->submit($form);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * TEST DE MODIFICATION D'UNE TÂCHE (À RÉALISER <-> TERMINÉE)
     */
    public function testToggleTaskAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks/1/toggle');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     * TEST DE SUPPRESSION D'UNE TÂCHE PAR UN SIMPLE UTILISATEUR
     */
    public function testDeleteTaskActionBySimpleUser()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('nicolas');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks/4/delete');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST DE SUPPRESSION D'UNE TÂCHE PAR UN MANAGER
     */
    public function testDeleteTaskByManager()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/tasks/2/delete');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

    }

    
}
