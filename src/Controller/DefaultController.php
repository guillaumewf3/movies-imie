<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\SearchMovieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route(
     *     "/{page}",
     *     name="home",
     *     defaults={"page":"1"},
     *     requirements={"page":"[0-9]+"}
     * )
     */
    public function home($page = 1, Request $request)
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);

        $searchData = [
            "keyword" => null,
            "minYear" => 1800,
            "maxYear" => date("Y")+1

        ];
        $searchMovieForm = $this->createForm(SearchMovieType::class, $searchData, ["method" => "GET"]);
        $searchMovieForm->handleRequest($request);
        $searchData = $searchMovieForm->getData();

        $movies = $movieRepo->findPaginated($page, $searchData["keyword"], $searchData["minYear"], $searchData["maxYear"]);

        return $this->render("default/home.html.twig", [
            "searchMovieForm" => $searchMovieForm->createView(),
            "movies" => $movies,
            "nextPage" => $page+1,
            "prevPage" => $page-1,
            "totalResults" => count($movies),
            "lastPage" => ceil(count($movies) / 50),
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
