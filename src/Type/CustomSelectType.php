<?php

namespace App\Type;

use App\Type\DataTransformer\PictureToArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class CustomSelectType extends AbstractType
{
    public function __construct(private PictureToArrayTransformer $transformer)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('class');
        $resolver->setDefaults([
            'compound' => false,
            'multiple' => true,
            'search' => '/search'
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    // Overriding all default config for ChoiceType for build view
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['expanded'] = false;
        $view->vars['placeholder'] = false;
        $view->vars['placeholder_in_choices'] = false;
        $view->vars['multiple'] = true;
        $view->vars['preferred_choices'] = [];
        $view->vars['choices'] = $this->choices($form->getData());
        $view->vars['choice_translation_domain'] = false;
        $view->vars['full_name'] .= '[]';
        $view->vars['required'] = false;
        $view->vars['attr']['data-remote'] = $options['search'];
        $view->vars['attr']['placeholder'] = 'Rechercher une image...';
    }

    public function getBlockPrefix(): string
    {
        return 'choice';
    }

    private function choices(?Collection $collection): array
    {
        return $collection
            ->map(function($picture) {
                return new ChoiceView($picture, (string)$picture->getId(), $picture->getImageName());
            })
            ->toArray();
    }
}