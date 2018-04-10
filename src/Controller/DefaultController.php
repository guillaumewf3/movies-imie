<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $movieRepo->findBy(
            [], //clauses where
            ["rating" => "DESC", "year" => "DESC"], //order by
            50,  //limit
            0); //offset

        return $this->render("default/home.html.twig", [
            "movies" => $movies
        ]);
    }

    /**
     * @Route("/legal-stuff", name="legal_stuff")
     */
    public function legalStuff()
    {
        return $this->render("default/legal.html.twig");
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutUs()
    {
        return $this->render("default/about.html.twig");
    }
}
