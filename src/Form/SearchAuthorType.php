<?php

namespace App\Form;

use App\DTO\SearchAuthorCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de l\'auteur: ',
                'required'=> false,
            ])
            ->add('orderBy', ChoiceType::class, [
                'label'=>'Trier par: ',
                'required'=> true,
                'choices'=>[
                    'Identifiant'=>'id',
                    'Nom'=>'name'
                ],
            ])
            ->add('direction', ChoiceType::class, [
                'label'=>'Sens du tri: ',
                'required'=> true,
                'choices'=>[
                    'Décroissant'=>'DESC',
                    'Croissant'=>'ASC'
                ],
            ])
            ->add('limit', NumberType::class, [
                'label'=>'Nombre de résultats: ',
                'required'=> true,
            ])
            ->add('page', NumberType::class, [
                'label'=>'Page: ',
                'required'=> true,
            ])
            ->add('send', SubmitType::class, [
                'label' => "Envoyer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchAuthorCriteria::class,
            'method' => 'GET', 
            'csrf_protection' => false
        ]);
    }
}
