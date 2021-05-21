<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tasktodo;
use App\Entity\Usertodo;
use App\Repository\TasktodoRepository;
use App\Repository\UsertodoRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var TasktodoRepository
     */
    private $taskRepo;

    /**
     * @var UsertodoRepository
     */
    private $userRepo;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
         TasktodoRepository $taskRepo, 
         UsertodoRepository $userRepo, 
         UserPasswordEncoderInterface $encoder
         )
    {
          $this->taskRepo = $taskRepo;
          $this->userRepo = $userRepo;
          $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
          $faker = \Faker\Factory::create('FR-fr');

          $this->taskRepo->fixtureIndex();
          $this->userRepo->fixtureIndex();


          // MANAGER AVEC LE ROLE_SUPER_ADMIN
          $user = new Usertodo();
          $user->setUsername('manager')
               ->setEmail('manager@test.com')
               ->setPassword('$2y$13$j44.vqfgRlcpUGLzU.wAGuN/oMM02AaeVkYyguwQjbiFOPULYOLEu')
               ->setCreatedAt($faker->dateTimeBetween('-1 months'))
               ->setFreshDate(new \Datetime())
               ->setRole('ROLE_SUPER_ADMIN')
          ;
          $manager->persist($user);

          // UTILISATEUR ANONYME
          $user = new Usertodo();
          $user->setUsername('anonyme')
               ->setEmail('anonymous@test.com')
               ->setPassword('$2y$13$j44.vqfgRlcpUGLzU.wAGuN/oMM02AaeVkYyguwQjbiFOPULYOLEu')
               ->setCreatedAt($faker->dateTimeBetween('-1 months'))
               ->setFreshDate(new \Datetime())
               ->setRole('ROLE_ANONYMOUS')
          ;
          $manager->persist($user);
          $this->addReference('anonymous-ref', $user);


          // UTILISATEUR AVEC LE ROLE_ADMIN
          $user = new Usertodo();
          $user->setUsername('paolo')
               ->setEmail('paolo@gmail.com')
               ->setPassword('$2y$13$j44.vqfgRlcpUGLzU.wAGuN/oMM02AaeVkYyguwQjbiFOPULYOLEu')
               ->setCreatedAt($faker->dateTimeBetween('-1 months'))
               ->setFreshDate(new \Datetime())
               ->setRole('ROLE_ADMIN')
          ;
          $manager->persist($user);
          $this->addReference('admin-ref', $user);


          // UTILISATEUR AVEC LE ROLE_USER
          $user = new Usertodo();
          $user->setUsername('nicolas')
               ->setEmail('nicolas@gmail.com')
               ->setPassword('$2y$13$j44.vqfgRlcpUGLzU.wAGuN/oMM02AaeVkYyguwQjbiFOPULYOLEu')
               ->setCreatedAt($faker->dateTimeBetween('-1 months'))
               ->setFreshDate(new \Datetime())
               ->setRole('ROLE_USER')
          ;
          $manager->persist($user);
          $this->addReference('user-ref', $user);

          // UTILISATEURS SUPPLÉMENTAIRES AVEC ROLE_USER
          for($i=1; $i<=24; $i++)
          {
               $user = new Usertodo();
               $user->setUsername('userTest'.$i)
                    ->setEmail('userTest'.$i.'@gmail.com')
                    ->setPassword($this->encoder->encodePassword($user, 'testtest'))
                    ->setCreatedAt($faker->dateTimeBetween('-1 months'))
                    ->setFreshDate(new \Datetime())
                    ->setRole('ROLE_USER')
               ;
               $manager->persist($user);

          }

          // UTILISATEURS SUPPLÉMENTAIRES AVEC ROLE_ADMIN
          $members = ['Jean', 'Julie', 'Vincent', 'Billy', 'Marion', 'Michel', 'Maxence'];

          foreach ($members as $key => $value) 
          {
               $user = new Usertodo();
               $user->setUsername($members[$key])
                    ->setEmail(''. $members[$key] .'@gmail.com')
                    ->setPassword($this->encoder->encodePassword($user, 'testtest'))
                    ->setCreatedAt($faker->dateTimeBetween('-1 months'))
                    ->setFreshDate(new \Datetime())
                    ->setRole('ROLE_ADMIN')
               ;
               $manager->persist($user);
          }

          // 4 TÂCHES ATTRIBUÉES À UN UTILISATEUR ANONYME
          for ($i = 0; $i < 8; $i++) {
               $task = new Tasktodo();
               $task->setTitle(ucfirst($faker->words(2, true)))
                    ->setContent($faker->sentences(2, true))
                    ->setCreatedAt($faker->dateTimeBetween('-1 months'))
                    ->setFreshDate(new \Datetime())
                    ->setIsDone(random_int(0,1))
                    ->setUsertodo($this->getReference('anonymous-ref'))
                    ;
               $manager->persist($task);
          }


          // 4 TÂCHES CRÉÉES PAR UN UTILISATEUR AVEC LE ROLE_ADMIN
          for ($i = 0; $i < 8; $i++) {
               $task = new Tasktodo();
               $task->setTitle(ucfirst($faker->words(2, true)))
                    ->setContent($faker->sentences(2, true))
                    ->setCreatedAt($faker->dateTimeBetween('-1 months'))
                    ->setFreshDate(new \Datetime())
                    ->setIsDone(random_int(0,1))
                    ->setUsertodo($this->getReference('admin-ref'))
                    ;
               $manager->persist($task);
          }

          // 4 TÂCHES CRÉÉES PAR UN UTILISATEUR AVEC LE ROLE_USER
          for ($i = 0; $i < 8; $i++) {
               $task = new Tasktodo();
               $task->setTitle(ucfirst($faker->words(2, true)))
                    ->setContent($faker->sentences(2, true))
                    ->setCreatedAt($faker->dateTimeBetween('-1 months'))
                    ->setFreshDate(new \Datetime())
                    ->setIsDone(random_int(0,1))
                    ->setUsertodo($this->getReference('user-ref'))
                    ;
               $manager->persist($task);
          }

          $manager->flush();

    }

}
