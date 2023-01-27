<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\PublishingHouse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminPublishingHouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la maison d\'édition ',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la maison d\'édition ',
                'required' => false,
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays d\'origine ',
                'required' => false,
            ])
            ->add('books', EntityType::class, [
                'label' => 'Choix du(des) livre(s) ',
                'required' => false,
                // Spécifie l'entité que l'on veut pouvoir selectionner
                'class' => Book::class,
                // Spécifie la propriété de la classe Author que l'on veut afficher -> ICI: author.name
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('Envoyer', SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublishingHouse::class,
        ]);
    }
}
