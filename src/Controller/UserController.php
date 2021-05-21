<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Usertodo;
use App\Repository\UsertodoRepository;
use App\Form\UsertodoType;
use App\Handler\PagingHandler;
use App\Security\Voter\UserVoter;

class UserController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorization;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var UsertodoRepository
     */
    private $usertodoRepo;

    /**
     * @var PagingHandler
     */
    private $pagingHandler;

    public function __construct(
        Security $security, 
        EntityManagerInterface $manager,
        AuthorizationCheckerInterface $authorization,
        UserPasswordEncoderInterface $encoder,
        UsertodoRepository $usertodoRepo, 
        PagingHandler $pagingHandler
        )
    {
        $this->security = $security;
        $this->manager = $manager;
        $this->authorization = $authorization;
        $this->encoder = $encoder;
        $this->usertodoRepo = $usertodoRepo;
        $this->pagingHandler = $pagingHandler;
    }

    /**
     * @Route("/users", name="user_list", methods={"GET"})
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $pageValues = $this->pagingHandler->handle($request);

        if (!$pageValues[0] || !$pageValues[1]) {
            $start = 0;
            $limit = 10;
        } else {
            $start = (int) strip_tags($pageValues[0]);
            $limit = (int) strip_tags($pageValues[1]);
        }

        $users = $this->usertodoRepo->findBy(array(), array('id' => 'DESC'));

        return $this->render('user/list.html.twig', [
            'limit' => $limit,
            'start' => $start,
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/create", name="user_create", methods={"GET","POST"})
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $user = new Usertodo();

        $form = $this->createForm(UsertodoType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->manager->persist($user);

            $this->manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit", requirements={"id": "\d+"}, methods={"GET","POST"})
     * @return Response
     */
    public function editAction(Usertodo $user, Request $request): Response
    {

        if (!$this->authorization->isGranted(UserVoter::UPDATE, $user)) {

			$this->addFlash('error', 'Vous ne pouvez pas modifier ce compte !');

			return $this->redirectToRoute('user_list');

		}
        
        $form = $this->createForm(UsertodoType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $user->setFreshDate(new \Datetime());

            $this->manager->persist($user);

            $this->manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/users/{id}/delete", name="user_delete", requirements={"id": "\d+"}, methods={"GET", "DELETE"})
     * @return RedirectResponse
     */
    public function deleteAction(Usertodo $user): RedirectResponse
    {

        if (!$this->authorization->isGranted(UserVoter::DELETE, $user)) {

			$this->addFlash('error', 'Vous ne pouvez pas supprimer ce compte !');

			return $this->redirectToRoute('user_list');

		}

        $this->manager->remove($user);
            
        $this->manager->flush();

        $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');

        return $this->redirectToRoute('user_list');
    }

}
