<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
// add moneytype
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
// add textareatype
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
// add datetype
use Symfony\Component\Form\Extension\Core\Type\DateType;
// add integertype
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

//add assert
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;



class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //adding assert as well, the validation rule
            ->add('titre', TextType::class)
            ->add('isbn', TextType::class)
            ->add('prix', MoneyType::class)
            ->add('description', TextareaType::class)
            ->add('datePublication', DateType::class)
            ->add('nombrePages', IntegerType::class)
            ->add('dateEdition', DateType::class)
            //->add('auteurs')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
