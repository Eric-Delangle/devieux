<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\LoginType;
use App\Entity\Category;
use ReCaptcha\ReCaptcha;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $slugify = new Slugify();
        $form = $this->createForm(UserType::class, $user);
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

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $slug = $slugify->slugify($user->getFirstName() . ' ' . $user->getLastName());
            $user->setSlug($slug);
            $user->getCategories(new Category());
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre compte a bien été créé');
            return $this->redirectToRoute('security_login');
        }
        // }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils)
    {

        //$form = $factory->createNamed("", LoginType::class, ['email' => $utils->getLastUsername()]);
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);
        dump($form);
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
