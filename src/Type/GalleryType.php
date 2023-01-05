<?php

namespace App\Type;

use App\DTO\GalleryDTO;
use App\Entity\Picture;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title", TextType::class, [
                'label' => 'Titre *',
                "help" => "Le titre de votre galerie",
                'required' => true
            ])
            ->add("cover", FileType::class, [
                'help' => 'Une image qui représentera votre galerie',
                "label" => "__default-img",
                'label_html' => true,
                "label_attr" => [
                    "class" => "image_label"
                ]
            ])
            ->add('published', CheckboxType::class, [
                "label" => "Public",
                'required' => false
            ])
            ->add('tag', EntityType::class, [
                "class" => Tag::class,
                "label" => 'Tag',
                'help' => 'Associez la galerie a un tag. Exemple: Mariage',
                'placeholder' => 'Sélectionnez un tag',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                "choice_label" => function ($tag) {
                    return $tag->getName();
                },
            ])
            ->add("uploads", CollectionType::class, [
                "label" => false,
                "entry_type" => FileType::class,
                "entry_options" => [
                    "row_attr" => ["class" => "image_row"],
                    "label" => "__default-img",
                    "label_html" => true,
                    "label_attr" => [
                        "class" => "image_label"
                    ]
                ],
                "allow_add" => true,
                "allow_delete" => true
            ])
            ->add("pictures", EntityType::class, [
                "class" => Picture::class,
                "label" => "Bibliothèque",
                "help" => "Vous pouvez choisir une ou plusieurs images depuis votre bibliothèque",
                'help_attr' => [
                    "class" => "mb-3"
                ],
                "multiple" => true,
                "expanded" => true,
                "required" => false,
                "choice_label" => function ($picture) {
                    return $picture->getpictureFileName();
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Sauvegarder'
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return "";
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GalleryDTO::class
        ]);
    }
}