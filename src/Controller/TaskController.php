<?php

namespace App\Controller;

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
    public function new(EntityManagerInterface $entityManager, Request $request, FormFactoryInterface $formFactory): Response
    {

        $variable = "Test";

        $task = new Task();
        $aufgabe = new Aufgabe();
        $task->setTask('Symfony lernen');
        $task->setDueDate(new \DateTimeImmutable('Tomorrow'));

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            echo "<script>console.log('$variable');</script>";
            $task = $form->getData();
            $date = $task->getDueDate()->format('d/m/Y');
            $aufgabe->setAufgabe($task->getTask());
            $aufgabe->setDatum($date);

            $entityManager->persist($aufgabe);
            $entityManager->flush();
        }
        return $this->render('task/new.html.twig', [
            'form' => $form,
        ]);
    }
}
