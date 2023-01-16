<?php

namespace App\Type;

use App\Entity\Thumbnail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ThumbnailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageFile', VichImageType::class, [
            'allow_delete' => false,
            'allow_extra_fields' => false,
            'download_label' => '...',
            'download_uri' => false,
            'help' => 'Une image qui représentera votre galerie',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Thumbnail::class
        ]);
    }
}