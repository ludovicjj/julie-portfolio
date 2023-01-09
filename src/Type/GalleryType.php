<?php

namespace App\Type;

use App\DTO\GalleryDTO;
use App\Entity\Gallery;
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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GalleryType extends AbstractType
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre *',
                "help" => "Le titre de votre galerie",
                'required' => true
            ])
            ->add('cover', FileType::class, [
                'mapped' => false,
                'help' => 'Une image qui représentera votre galerie',
                'label' => "__default-img",
                'label_html' => true,
                'label_attr' => [
                    'class' => "image_label"
                ]
            ])
            ->add('published', CheckboxType::class, [
                "label" => 'public',
                'required' => false,
                'help' => 'Définissez si la galerie est privée ou publique',
            ])
            ->add('tag', EntityType::class, [
                "class" => Tag::class,
                "label" => 'Tag',
                'help' => 'Associez la galerie à un tag. Exemple: Mariage',
                'placeholder' => 'Sélectionnez un tag',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                "choice_label" => function ($tag) {
                    return $tag->getName();
                },
            ])
            ->add("uploads", CollectionType::class, [
                'mapped' => false,
                'label' => false,
                'entry_type' => FileType::class,
                'entry_options' => [
                    'label' => "__default-img",
                    'label_html' => true,
                    'label_attr' => [
                        'class' => 'image_label'
                    ]
                ],
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add("pictures", CustomSelectType::class, [
                'label' => 'Bibliothèque',
                'class' => Picture::class,
                'search' => $this->urlGenerator->generate('api_picture_search')
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
            'data_class' => Gallery::class
        ]);
    }
}