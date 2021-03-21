<?php

namespace App\Controller;

use App\Entity\Tasktodo;
use App\Form\TasktodoType;
// use App\Repository\TasktodoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends AbstractController
{

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository('App:Tasktodo')->findAll()
            ]);
    }

    /**
     * @Route("/tasks/done", name="task_done")
     */
    public function listDone()
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository('App:Tasktodo')->findBy(['isDone' => true])
            ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Tasktodo();

        $form = $this->createForm(TasktodoType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $task->setUsertodo($this->getUser());

            $em->persist($task);

            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Tasktodo $task, Request $request)
    {
        $form = $this->createForm(TasktodoType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $this->getDoctrine()->getManager()->flush();

            // AJOUTÉ POUR LA DATE DE MISE À JOUR
            $em = $this->getDoctrine()->getManager();

            $task->setFreshDate(new \Datetime());

            $em->persist($task);

            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Tasktodo $task)
    {
        $task->toggle(!$task->isDone());

        $this->getDoctrine()->getManager()->flush();

        // AJOUTÉ POUR GÉRER LES MESSAGES TÂCHE TERMINÉE OU RÉOUVERTE
        if ($task->isDone() == true) {

            $this->addFlash('success', sprintf('La tâche " %s " a bien été marquée comme faite.', $task->getTitle()));

        } else {

            $this->addFlash('success', sprintf('La tâche " %s " est réouverte.', $task->getTitle()));

        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Tasktodo $task)
    {
        // AJOUTÉ POUR EMPÊCHER QU'UN UTILISATEUR SIMPLE NE SUPPRIME UNE TÂCHE DONT IL N'EST PAS L'AUTEUR
        // On vérifie : si l'utilisateur connecté est différent de l'auteur de la tâche
        // if ($this->security->getUser() !== $task->getUsertodo())
        if ($this->getUser() !== $task->getUsertodo()) 
        {
            // Et si l'utilisateur connecté n'a pas le rôle ADMIN
            if(!$this->isGranted('ROLE_ADMIN'))
            {
                // Alors Erreur !
                $this->addFlash('error', 'Erreur. Opération réservée aux auteurs des tâches ou aux administrateurs');

                return $this->redirectToRoute('task_list');
            }

        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');

    }
    
}
