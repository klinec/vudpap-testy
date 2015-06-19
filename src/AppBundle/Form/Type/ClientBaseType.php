<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientBaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ages = [];
        for ($i = 6; $i <= 99; $i++) {
            $ages[$i] = $i;
        }

        $builder
            ->add(
                'age',
                'choice',
                [
                    'label' => 'Vek',
                    'placeholder' => 'Vek',
                    'choices' => $ages
                ]
            )
            ->add(
                'gender',
                'choice',
                [
                    'label' => 'Pohlavie',
                    'placeholder' => 'Pohlavie',
                    'choices' => [
                        'm' => 'Muž',
                        'f' => 'Žena'
                    ]
                ]
            )
            ->add(
                'language',
                'choice',
                [
                    'placeholder' => 'Jazyk, ktorým doma najčastejšie rozprávate',
                    'choices' => [
                        'slovak' => 'Slovenský',
                        'gipsy' => 'Rómsky',
                        'czech' => 'Český',
                        'polish' => 'Poľský',
                        'hungarian' => 'Maďarský',
                    ]
                ]
            );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'client_base';
    }
}
