<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit :',
                'attr' => ['placeholder' => 'nom du produit']
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description du produit :',
                'attr' => ['placeholder' => 'Description du produit']
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Url de l\'image',
                'attr' => ['placeholder' => 'Url de l\'image']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit :',
                'attr' => ['placeholder' => 'Prix du produit'],
                'divisor' => 100
            ])
            ->add('category', EntityType::class, [
                'label' => 'Prix du produit',
                'placeholder' => '-- Selectionnez une categorie --',
                'class' => Category::class,
                'choice_label' => 'name'
            ]);

        //$builder->get('price')->addModelTransformer(new CentsTransformer);
        /*
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

            $product = $event->getData();
            if ($product->getPrice() !== null) {
                $product->setPrice($product->getPrice() * 100);
            }
        });


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            
            $product = $event->getData();
            if ($product->getPrice() !== null) {
                $product->setPrice($product->getPrice() / 100);
            }
        });
        */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
