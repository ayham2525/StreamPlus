<?php 
namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addressLine1', TextType::class)
            ->add('addressLine2', TextType::class, [
                'required' => false
            ])
            ->add('city', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('state', TextType::class)
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'USA' => 'USA',
                    'Canada' => 'Canada',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
