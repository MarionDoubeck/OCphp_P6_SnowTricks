<?php
// src/Form/MediaType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Ajouter un média depuis votre appareil : ',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => [
                            'image/*',
                            'video/*',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier valide (image ou vidéo)',
                    ]),
                ],
            ])

            ->add('externalLink', UrlType::class, [
                'label' => 'Ajouter un média avec un lien externe : ',
                'required' => false,
                // Ajoutez ici les contraintes pour le lien externe si nécessaire
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configurez les options ici si nécessaire
        ]);
    }
}
