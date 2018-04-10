<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MovieController extends Controller
{
    /**
     * @Route("/movie/{id}", name="movie_detail")
     */
    public function detail($id)
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        //récupère une ligne en fonction de la clef primaire
        $movie = $movieRepo->find($id);
            //ou...
            //->findOneBy(["id" => $id]);
            //->findOneById($id);

        return $this->render('movie/detail.html.twig', [
            "movie" => $movie
        ]);
    }


}
