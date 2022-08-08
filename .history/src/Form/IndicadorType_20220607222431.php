<?php

namespace App\Form;

use App\Entity\Indicador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndicadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('formula')
            ->add('ente', null, [
                'label' => 'Org. supervigilancia/acreditación',
                'placeholder' => 'Seleccione un ente',
                'required' => true,
            ])
            ->add('categoria');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Indicador::class,
        ]);
    }
}
