<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovieController extends Controller
{
    /**
     * @Route("/movie/{id}", name="movie_detail")
     */
    public function detail($id,
                           Request $request,
                            EntityManagerInterface $em)
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        //récupère une ligne en fonction de la clef primaire
        $movie = $movieRepo->find($id);

        //ou... //->findOneBy(["id" => $id]); //->findOneById($id);

        //crée une nouvelle review vide
        $review = new Review();
        //crée le formulaire en lui associant notre review vide
        $reviewForm = $this->createForm(ReviewType::class, $review);

        //prend les données envoyées et les injecte dans $review
        $reviewForm->handleRequest($request);

        //renseigne programmatiquement la date de création
        $review->setDateCreated(new \DateTime());

        //si le formulaire est soumis...
        if ($reviewForm->isSubmitted() && $reviewForm->isValid()){
            //on sauvegarde l'entité en base de données
            $em->persist($review);
            $em->flush();

            //stocke un message en session pour affichage sur
            //la page suivante
            $this->addFlash("success", "Your review has been published!");
            //redirige l'utilisateur ici-même pour vider le formulaire
            return $this->redirectToRoute('movie_detail', ["id" => $id]);
        }

        return $this->render('movie/detail.html.twig', [
            "movie" => $movie,
            "reviewForm" => $reviewForm->createView()
        ]);
    }


}
