<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Groupe de la figure',
                'attr' => [
                    'class' => 'my-2',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de la figure',
                'attr' => [
                    'class' => 'form-control my-2',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la figure',
                'attr' => [
                    'class' => 'form-control my-2',
                    'rows' => 6,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez la description du trick',
                    ]),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'La description du trick doit faire entre {{ limit }} et 4096 caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            
           /*  ->add('media.type', ChoiceType::class, [
                'choices' => [
                    'Image' => 'image',
                    'Video' => 'video',
                ],
            ])
            ->add('media.path', TextType::class)
            ->add('media.description', TextareaType::class) */

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
