<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Media;
use App\Form\LoginType;
use App\Form\RecruType;
use App\Entity\Category;
use App\Entity\Recruter;
use App\Form\RegisterForm;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription_dev", name="security_registration_dev")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $media = new Media();
        $slugify = new Slugify();


        $form = $this->createForm(RegisterForm::class, ['user' => $user, 'imageFile' => $media]);

        $form->handleRequest($request);

        /* captcha */
        /*
        $recaptcha = new ReCaptcha('');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
  
        if (!$resp->isSuccess()) {
         // $this->addFlash('N\'oubliez pas de cocher la case "Je ne suis pas un robot"');
        } else {
       */
        if ($form->isSubmitted() && $form->isValid()) {

            // Si je ne fait pas ça l'avatar n'est pas uploadé
            $uploadedFile = $form['media']->getData();
            $manager->persist($uploadedFile);
            $manager->flush();
            // Suite du formulaire de création de compte 
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $slug = $slugify->slugify($user->getFirstName() . ' ' . $user->getLastName());
            $user->setSlug($slug);
            $user->getCategories(new Category());
            $user->setRegisteredAt(new \DateTime());

            $user->addMedium($uploadedFile);

            $manager->persist($user);


            $manager->flush();
            $this->addFlash('success', 'Votre compte a bien été créé');
            return $this->redirectToRoute('security_login');
        }



        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),

        ]);
    }


    /**
     * @Route("/inscription_rec", name="security_registration_rec")
     */
    public function registrationRec(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $recruter = new Recruter();
        $media = new Media();
        $slugify = new Slugify();
        $form = $this->createForm(RecruType::class, $recruter);
        $form->handleRequest($request);

        /* captcha */
        /*
        $recaptcha = new ReCaptcha('');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
  
        if (!$resp->isSuccess()) {
         // $this->addFlash('N\'oubliez pas de cocher la case "Je ne suis pas un robot"');
        } else {
       */
        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($recruter, $recruter->getPassword());
            $recruter->setPassword($hash);
            $slug = $slugify->slugify($recruter->getFirstName() . ' ' . $recruter->getLastName());
            $recruter->setSlug($slug);
            $recruter->setRoles(["ROLES_RECRUTER"]);
            $recruter->setRegisteredAt(new \DateTime());
            $manager->persist($recruter);
            $manager->flush();
            $this->addFlash('success', 'Votre compte a bien été créé');
            return $this->redirectToRoute('security_login');
        }
        // }

        return $this->render('security/registrationRec.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils)
    {

        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render(
            'security/login.html.twig',
            [
                'formView' => $form->createView(),
                'error' => $utils->getLastAuthenticationError(),
            ],
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
