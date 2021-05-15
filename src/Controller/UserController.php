<?php

namespace App\Controller;

use App\Entity\Usertodo;
use App\Form\UsertodoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends AbstractController
{

    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {

        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('App:Usertodo')->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new Usertodo();

        $form = $this->createForm(UsertodoType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            $em->persist($user);

            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(Usertodo $user, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        // AJOUTÉ POUR EMPÊCHER UN ADMINISTRATEUR DE MODIFIER SON PROPRE COMPTE
        if ($this->getUser()->getId() === $user->getId()) 
        {
            // https://symfony.com/doc/current/controller.html#managing-errors-and-404-pages
            throw $this->createNotFoundException('Access Denied.');
        }
        
        $form = $this->createForm(UsertodoType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $password = $passwordEncoder->encodePassword($user, $user->getPassword());

            $user->setPassword($password);

            // AJOUTÉ POUR LA DATE DE MISE À JOUR
            $user->setFreshDate(new \Datetime());

            $em->persist($user);

            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/users/{id}/delete", name="user_delete")
     */
    public function deleteAction(Usertodo $user): Response
    {
        // AJOUTÉ POUR EMPÊCHER UN ADMINISTRATEUR DE SUPPRIMER SON PROPRE COMPTE
        if ($this->getUser()->getId() === $user->getId()) 
        {
            // https://symfony.com/doc/current/controller.html#managing-errors-and-404-pages
            throw $this->createNotFoundException('Access Denied.');
        }

        // AJOUTÉ POUR SUPPRIMER DES UTILISATEURS 
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($user);

        $em->flush();

        $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');

        return $this->redirectToRoute('user_list');
    }

}
