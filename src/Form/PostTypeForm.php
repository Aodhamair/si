<?php


namespace App\Form;


use App\Entity\Posts;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -> add('title')
            -> add('content')
            -> add(
                'category',
                EntityType::class,
                [
                    'class'=>Category::class,
                    'choice_label'=>function ($category) {
                        return $category->getName();
                    },
                    'label'=> 'Kategorie',
                    'required'=>true
                ]
            )
            -> add('save', SubmitType::class, ['label'=>'Zapisz']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>Posts::class
        ]);
    }

}