<?php 
// src/Form/PaymentType.php
namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Payment $payment */
        $payment = $options['data'];  // Get the Payment entity object

        $builder
            ->add('creditCardNumber', TextType::class)
            ->add('expirationDate', TextType::class, [
                'label' => 'Expiration Date (MM/YY)',
                'data' => $payment ? $payment->getFormattedExpirationDate() : null, // Use the formatted expiration date here
                'attr' => ['placeholder' => 'MM/YY'],
                'required' => true,
            ])
            ->add('cvv', NumberType::class, [
                'attr' => ['maxlength' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
