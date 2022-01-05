<?php

namespace AddressBookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('zip', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('country', CountryType::class, [
                'data' => 'DE',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('phonenumber', TelType::class, [
                'required'=> false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'required'=> false
            ])
            ->add('email', EmailType::class, [
                'required'=> false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AddressBookBundle\Entity\Contact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'addressbookbundle_contact';
    }


}
