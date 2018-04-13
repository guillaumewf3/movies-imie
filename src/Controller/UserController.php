<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * C'est Fabien qui gère la déconnexion
     *
     * @Route("/logout", name="logout")
     */
    public function logout(){}


    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {

        //on bloque l'accès à cette page si le user
        //est connecté
        if ($this->getUser()){
            return $this->redirectToRoute("home");
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }


    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request,
                             EntityManagerInterface $em,
                             UserPasswordEncoderInterface $encoder)
    {
        $mailer = $this->container->get('mailer');
        $mailer->send();

        $user = new User();
        $registerForm = $this->createForm(RegisterType::class, $user);

        //prend les données soumises et les injecte dans notre $user
        $registerForm->handleRequest($request);

        //hydrate les propriétés manquantes
        $user->setDateCreated(new \DateTime());
        $user->setRoles(["ROLE_USER"]); //attention, un tableau !

        if ($registerForm->isSubmitted() && $registerForm->isValid()){
            //hashe le mot de passe
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Your account has been created! Yippi!");
            return $this->redirectToRoute("home");
        }

        return $this->render("user/register.html.twig", [
            "registerForm" => $registerForm->createView()
        ]);
    }
}
