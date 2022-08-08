<?php

namespace App\Form;

use App\Entity\Registro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('valor')
            ->add('ente', null, [
                'label' => 'Org. supervigilancia/acreditación',
                'placeholder' => 'Seleccione',
                'required' => true,
            ])
            ->add('categoria')
            ->add('indicador')
            ->add('periodo');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Registro::class,
        ]);
    }
}
