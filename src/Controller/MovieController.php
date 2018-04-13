<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Entity\WatchlistItem;
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

        $reviewRepo = $this->getDoctrine()->getRepository(Review::class);
        $reviews = $reviewRepo->findMovieReviewsWithUser($movie);

        //ou... //->findOneBy(["id" => $id]); //->findOneById($id);

        //crée une nouvelle review vide
        $review = new Review();
        //crée le formulaire en lui associant notre review vide
        $reviewForm = $this->createForm(ReviewType::class, $review);

        //prend les données envoyées et les injecte dans $review
        $reviewForm->handleRequest($request);

        //renseigne programmatiquement la date de création
        $review->setDateCreated(new \DateTime());
        //et l'associe au film
        $review->setMovie($movie);

        //si le formulaire est soumis, valide et que le user est connecté
        if ($reviewForm->isSubmitted() &&
            $reviewForm->isValid() &&
            $this->getUser()){

            //associe à son auteur
            $review->setAuthor($this->getUser());

            //on sauvegarde l'entité en base de données
            $em->persist($review);
            $em->flush();

            //stocke un message en session pour affichage sur
            //la page suivante
            $this->addFlash("success", "Your review has been published!");
            //redirige l'utilisateur ici-même pour vider le formulaire
            return $this->redirectToRoute('movie_detail', ["id" => $id]);
        }

        //vérifie si le film est déjà dans la watchlist
        $inWatchlist = false;
        if ($this->getUser()){
            $watchlistRepo = $this->getDoctrine()->getRepository(WatchlistItem::class);
            if ($watchlistRepo->findOneBy([
                "user" => $this->getUser(),
                "movie" => $movie,
            ])){
                $inWatchlist = true;
            }
        }

        return $this->render('movie/detail.html.twig', [
            "movie" => $movie,
            "reviews" => $reviews,
            "reviewForm" => $reviewForm->createView(),
            "inWatchlist" => $inWatchlist
        ]);
    }


}
