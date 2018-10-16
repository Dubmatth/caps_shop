<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Votre prénom'])
            ->add('lastName', TextType::class, ['label' => 'Votre nom'])
            ->add('email', EmailType::class, ['label' => 'Votre email'])
            ->add('userName', TextType::class, ['label' => 'Nom d\'utilisateur'])
            ->add('password', TextType::class, ['label' => 'Mot de passe'])
/*            ->add('roles', IntegerType::class, ['label' => 'Role'])*/
            ->add('confirmPassword', PasswordType::class, ['label' => 'Confirmer votre mot de passe'])
            ->add('Envoyer', SubmitType::class)

            /*FIXME Ajout avatar + story + roles*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
