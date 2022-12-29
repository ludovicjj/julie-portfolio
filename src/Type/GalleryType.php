<?php

namespace App\Type;

use App\Entity\Gallery;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, [
                'label' => 'titre de la galerie *',
                "help" => "Le titre de votre galerie",
            ])
            ->add("description", TextareaType::class, [
                'label' => 'Description *',
                'required' => true,
                'help' => 'Une courte description de votre galerie'
            ])
            ->add("images", CollectionType::class, [
                'label' => false,
                "mapped" => false,
                'entry_type' => FileType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add("collection", EntityType::class, [
                "class" => Picture::class,
                "label" => "Bibliothèque",
                "help" => "Vous pouvez choisir une ou plusieurs images depuis votre bibliothèque",
                'help_attr' => [
                    "class" => "mb-3"
                ],
                "multiple" => true,
                "expanded" => true,
                "mapped" => false,
                "required" => false,
                "choice_label" => function ($picture) {
                    return $picture->getpictureFileName();
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}