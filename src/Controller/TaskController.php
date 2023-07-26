<?php

namespace App\Controller;

use DateTime;
use App\Service\FileUploader;
use App\Form\Type\TaskType;
use App\Entity\Task;
use App\Entity\Aufgabe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;

class TaskController extends AbstractController
{
    public function new(
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager,
        Request $request,
        Security $security
    ) {
        $task = new Task();
        $aufgabe = new Aufgabe();
        $task->setTask('Symfony lernen');
        $task->setDueDate(new \DateTimeImmutable('Tomorrow'));

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $bildFile = $form->get('bild')->getData();

            if ($bildFile) {
                $bildName = $fileUploader->upload($bildFile);
                $aufgabe->setBildName($bildName);
            }

            $user = $security->getUser();
            if ($user) {
                $aufgabe->setUserId($user->getId());
            }

            $task = $form->getData();
            $date = $task->getDueDate()->format('d/m/Y');
            $aufgabe->setAufgabe($task->getTask());
            $aufgabe->setDatum($date);
            $entityManager->persist($aufgabe);
            $entityManager->flush();
            return $this->redirectToRoute('todo');
        }

        $user = $security->getUser();
        $userId = $user ? $user->getId() : null;
        $posts = $this->getFilteredTasks($entityManager, $userId);

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
            'pass' => $posts,
        ]);
    }

    private function getFilteredTasks(EntityManagerInterface $entityManager, ?int $userId): array
    {
        if (!$userId) {
            return [];
        }
        $queBe = $entityManager->createQueryBuilder();
        $posts = $queBe
            ->select('a')
            ->from(Aufgabe::class, 'a')
            ->where('a.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('a.Datum', 'DESC')
            ->getQuery()
            ->getResult();

        return $posts;
    }

    public function deleteTask(EntityManagerInterface $entityManager, Request $request)
    {
        $postId = $request->attributes->get('id');

        $aufgabe = $entityManager->getRepository(Aufgabe::class)->find($postId);
        $entityManager->remove($aufgabe);
        $entityManager->flush();
        return $this->redirectToRoute('todo');
    }
}
