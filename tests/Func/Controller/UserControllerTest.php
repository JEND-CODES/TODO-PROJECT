<?php

namespace Tests\Func\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UsertodoRepository;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * TEST D'ACCÈS À LA LISTE DES UTILISATEURS
     */
    public function testListAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/users');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());

        $this->assertSame("Nom d'utilisateur", $crawler->filter('th')->text());

    }

    /**
     * TESTS D'ACCÈS AUX ROUTES PROTÉGÉES DE GESTIONS DES UTILISATEURS
     */
    public function testProtectedPathUsersAccess()
    {
        $paths = [
            ['GET', '/users'],
            ['GET', '/users/create'],
            ['GET', '/users/5/edit']
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
     * TEST D'ACCÈS À LA LISTE PAGINÉE DES UTILISATEURS
     */
    public function testUsersListPaginated()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/users?start=10&limit=10');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST D'ERREUR GÉNÉRÉE LIÉE À LA PAGINATION (TROP D'ITEMS DEMANDÉS)
     */
    public function testErrorForTooManyUsersRequested()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/users?start=1&limit=101');

        $this->assertSame(500, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST DE CRÉATION D'UN NOUVEL UTILISATEUR
     */
    public function testCreateAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/users/create');
        
        $form = $crawler->selectButton('Ajouter')->form();

        $form['usertodo[username]'] = 'Danielle';

        $form['usertodo[email]'] = 'danielle@test.com';

        $form['usertodo[password][first]'] = 'testtest';

        $form['usertodo[password][second]'] = 'testtest';

        $form['usertodo[role]'] = 'ROLE_USER';

        $this->client->submit($form);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST DE MODIFICATION D'UN UTILISATEUR
     */
    public function testEditAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/users/4/edit');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('input[name="usertodo[username]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();

        $form['usertodo[username]'] = 'nicolas';

        $form['usertodo[password][first]'] = 'testtest';

        $form['usertodo[password][second]'] = 'testtest';

        $form['usertodo[email]'] = 'nicolas@gmail.com';

        $form['usertodo[role]'] = 'ROLE_USER';

        $this->client->submit($form);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());

    }

    /**
     * TEST D'ACCÈS INTERDIT À LA PAGE DE MISE À JOUR D'UN UTILISATEUR
     */
    public function testEditActionWithDeniedAccess()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testAdmin = $usertodoRepo->findOneByUsername('paolo');

        $this->client->loginUser($testAdmin);

        $this->client->request('GET', '/users/3/edit');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST DE SUPPRESSION D'UN UTILISATEUR
     */
    public function testDeleteAction()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $this->client->request('GET', '/users/3/delete');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

    }

    /**
     * TEST D'ACCÈS INTERDIT À LA SUPPRESSION D'UN UTILISATEUR
     */
    public function testDeleteActionWithDeniedAccess()
    {
        $usertodoRepo = self::$container->get(UsertodoRepository::class);

        $testManager = $usertodoRepo->findOneByUsername('manager');

        $this->client->loginUser($testManager);

        $crawler = $this->client->request('GET', '/users/1/delete');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());

    }
    
}
