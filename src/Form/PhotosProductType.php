<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\PhotosProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PhotosProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('link', FileType::class, [
                'label' => 'Photo',
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhotosProduct::class,
        ]);
    }
}
