<?php 
namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Payment;
use App\Form\UserType;
use App\Form\AddressType;
use App\Form\PaymentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class OnboardingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user-info', name: 'user_info')]
public function userInfo(Request $request): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

        $this->entityManager->persist($user);
        $this->entityManager->flush();

     
        if ($user->getSubscriptionType() == 'premium') {
            return $this->redirectToRoute('address_info', ['userId' => $user->getId()]);
        } else {
            return $this->redirectToRoute('confirmation', ['userId' => $user->getId()]);
        }
    }

    return $this->render('onboarding/user_info.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/address-info/{userId}', name: 'address_info')]
    public function addressInfo(Request $request, $userId): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user);  
    
          
            $this->entityManager->persist($address);
            $this->entityManager->flush();
    
        
            if ($user->getSubscriptionType() === 'premium') {
                return $this->redirectToRoute('payment_info', ['userId' => $user->getId()]);
            } else {
                return $this->redirectToRoute('confirmation', ['userId' => $user->getId()]);
            }
        }
    
        return $this->render('onboarding/address_info.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/payment-info/{userId}', name: 'payment_info')]
    public function paymentInfo(Request $request, $userId): Response
    {
       
        $user = $this->entityManager->getRepository(User::class)->find($userId);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
       
        if ($user->getSubscriptionType() !== 'premium') {
            return $this->redirectToRoute('confirmation', ['userId' => $user->getId()]);
        }
    
      
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           
            $payment->setUser($user); 
           
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('confirmation', ['userId' => $user->getId()]);
        }
    
        return $this->render('onboarding/payment_info.html.twig', [
            'form' => $form->createView(),
            'user' => $user, 
        ]);
    }
    

    

    #[Route('/confirmation/{userId}', name: 'confirmation')]
    public function confirmation(Request $request, $userId): Response
    {
       
        $user = $this->entityManager->getRepository(User::class)->find($userId);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
      
        $address = $user->getAddress();
        $payment = $user->getPayment();
    
        if ($user->getSubscriptionType() == 'premium' && (!$address || !$payment)) {
            throw $this->createNotFoundException('Address or payment information is missing for this user.');
        }
    
        return $this->render('onboarding/confirmation.html.twig', [
            'user' => $user,
            'address' => $address,
            'payment' => $payment,
        ]);
    }

    #[Route('/submit-onboarding', name: 'submit_onboarding')]
    public function submitOnboarding(Request $request): Response
    {
    
        return $this->redirectToRoute('confirmation', ['userId' => $request->get('userId')]);
    }

}
