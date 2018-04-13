<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Entity\WatchlistItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WatchlistController extends Controller
{
    /**
     * @Route("/watchlist", name="watchlist")
     */
    public function show()
    {
        $watchlistRepo = $this->getDoctrine()->getRepository(WatchlistItem::class);
        $watchlistItems = $watchlistRepo->findBy(["user" => $this->getUser()]);

        return $this->render("watchlist/show.html.twig", [
            "watchlistItems" => $watchlistItems
        ]);
    }

    /**
     * @Route("/watchlist/add/{id}", name="watchlist_add")
     */
    public function add($id,
                        EntityManagerInterface $em,
                        Request $request)
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepo->find($id);

        $user = $this->getUser();

        $watchlistItem = new WatchlistItem();
        $watchlistItem->setMovie($movie);
        $watchlistItem->setUser($user);
        $watchlistItem->setDateCreated(new \DateTime());

        $em->persist($watchlistItem);
        $em->flush();

        if ($request->isXmlHttpRequest()){
            echo "ok";
            die();
        }
        else {
            $this->addFlash("success", "Movie added!");
            return $this->redirectToRoute("movie_detail", ["id" => $id]);
        }
    }

    /**
     * @Route("/watchlist/remove/{id}", name="watchlist_remove")
     */
    public function remove($id,
                        EntityManagerInterface $em)
    {
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepo->find($id);

        $user = $this->getUser();

        $watchlistRepo = $this->getDoctrine()->getRepository(WatchlistItem::class);
        $watchlistItem = $watchlistRepo->findOneBy([
            "movie" => $movie,
            "user" => $user
        ]);

        $em->remove($watchlistItem);
        $em->flush();

        $this->addFlash("success", "Movie removed!");
        return $this->redirectToRoute("movie_detail", ["id" => $id]);
    }
}