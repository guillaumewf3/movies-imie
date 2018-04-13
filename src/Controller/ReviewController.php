<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReviewController extends Controller
{
    /**
     * @Route("/review/{id}/delete", name="review_delete")
     */
    public function delete($id, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        if (!$user){
            throw $this->createAccessDeniedException();
        }

        $reviewRepo = $this->getDoctrine()->getRepository(Review::class);
        $review = $reviewRepo->find($id);

        if (!$review){
            throw $this->createNotFoundException();
        }

        if ($review->getAuthor() === $user){
            $em->remove($review);
            $em->flush();

            $this->addFlash("success", "Your review has been deleted!");
            return $this->redirectToRoute("movie_detail", [
                "id" => $review->getMovie()->getId()
            ]);
        }

        $this->addFlash("warning", "Something went wrong!");
        return $this->redirectToRoute("movie_detail", [
            "id" => $review->getMovie()->getId()
        ]);
    }

}