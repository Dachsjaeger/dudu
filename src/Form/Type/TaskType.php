<?php

namespace App\Form\Type;

use App\Entity\Task;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentDateTime = new \DateTime();
        $currentDate = $currentDateTime->format('Y-m-d');
        $builder
            ->add('task', TextType::class, ['label' => 'Aufgabe',])
            ->add('dueDate', DateType::class, [
                'label' => 'Fertig bis:',
                'format' => 'yyyy-MM-dd',
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'max' => '2030-12-31',
                    'min' => $currentDate,
                ],
            ])
            ->add('bild', FileType::class, [
                'label' => 'BILD',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'application/png',
                            'application/jpg',
                        ],
                        'mimeTypesMessage' => 'FALSCH',
                    ])
                ],
            ])
            ->add('nimmMeineDaten', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Nimm meine Daten!',
            ])
            ->add('save', SubmitType::class, ['label' => 'Speichern',])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
