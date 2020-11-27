<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Movie;
use App\Entity\Genre;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre/search", name="search")
     */
    public function search(Request $request)
    {
        $genres = $this->getDoctrine()->getRepository(Genre::class)->findAll();

        return $this->render('genre/search.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/genre/{id}", name="search_genre")
     */
    public function searchByGenre($id)
    {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findBy(['genre' => $id]);
        $genres = $this->getDoctrine()->getRepository(Genre::class)->findBy(['id' => $id]);

        return $this->render('genre/search-result.html.twig', [
            'movies' => $movies,
            'genres' => $genres,
        ]);
    }

}
