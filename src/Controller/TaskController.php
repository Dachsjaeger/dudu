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

class TaskController extends AbstractController
{
    public function new(FileUploader $fileUploader, EntityManagerInterface $entityManager, Request $request)
    {

        $variable = "Test";

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
            echo "<script>console.log('$variable');</script>";
            $task = $form->getData();
            $date = $task->getDueDate()->format('d/m/Y');
            $aufgabe->setAufgabe($task->getTask());
            $aufgabe->setDatum($date);
            $entityManager->persist($aufgabe);
            $entityManager->flush();
            return $this->redirectToRoute('todo');
        }
        $queBe = $entityManager->createQueryBuilder();
        $posts = $queBe
                ->select('a')
                ->from(Aufgabe::class, 'a')
                ->orderBy('a.Datum', 'DESC')
                ->getQuery()
                ->getResult();
        usort($posts, function ($a, $b) {
            $dateA = DateTime::createFromFormat('d/m/Y', $a->getDatum());
            $dateB = DateTime::createFromFormat('d/m/Y', $b->getDatum());
            return $dateA <=> $dateB;
        });
        $display_limit = 110;
        foreach ($posts as $lu) {
            $txt = strip_tags($lu->getAufgabe());
            $pass[] = [
            'id' => $lu->getId(),
            'datum' => $lu->getDatum(),
            'Aufgabe' => $lu->getAufgabe(),
            'bildName' => $lu->getBildName(),
            ];
        }
        return $this->render('task/new.html.twig', [
        'form' => $form->createView(),
        'pass' => $pass,
        ]);
    }

    public function suc(): Response
    {
        return $this->render('task/suc.html.twig');
    }

    public function show(EntityManagerInterface $entityManager)
    {
        $queBe = $entityManager->createQueryBuilder();
        $posts = $queBe
                    ->select('a')
                    ->from(Aufgabe::class, 'a')
                    ->orderBy('a.Datum', 'DESC')
                    ->getQuery()
                    ->getResult();
        usort($posts, function ($a, $b) {
            $dateA = DateTime::createFromFormat('d/m/Y', $a->getDatum());
            $dateB = DateTime::createFromFormat('d/m/Y', $b->getDatum());
            return $dateA <=> $dateB;
        });
        $display_limit = 110;
        foreach ($posts as $lu) {
            $txt = strip_tags($lu->getAufgabe());
            $pass[] = [
                'id' => $lu->getId(),
                'datum' => $lu->getDatum(),
                'Aufgabe' => $lu->getAufgabe()
            ];
        }
        return $this->render('task/post.html.twig', [
            'pass' => $pass
        ]);
    }

    public function deleteTask(int $id, EntityManagerInterface $entityManager)
    {
        $task = $entityManager->getRepository(Aufgabe::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Wo Aufgabe');
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('todo');
    }
}
