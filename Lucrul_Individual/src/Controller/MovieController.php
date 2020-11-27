<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\MovieType;
use App\Entity\Movie;
use App\Entity\Genre;

class MovieController extends AbstractController
{
    /**
     * @Route("/add-default-movie", name="add_default_movie")
     */
    public function addDefaultMovie(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $genre = new Genre();
        $genre = $this->getDoctrine()->getRepository(Genre::class)->find(1);

        $movie = new Movie();
        $movie->setName('MovieName');
        $movie->setGenre($genre);
        $movie->setPremiere(new \DateTime('2020-10-20'));

        // tell Doctrine you want to (eventually) save the Movie (no queries yet)
        $entityManager->persist($movie);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        $this->addFlash('success','Filmul a fost adaugat');

        return $this->redirect($this->generateUrl('index'));
    }
    
    /**
     * @Route("/add-movie", name="add_movie")
     */
    public function addMovie(Request $request): Response
    {
        // just setup a fresh $task object (remove the example data)
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
            $this->addFlash('success','Filmul a fost adaugat');

            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit-movie/{id}", name="edit_movie")
     */
    public function edit(Request $request, $id): Response
    {
        $movie = new Movie();
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','Filmul a fost modificat');

            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('movie/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete-movie/{id}", name="delete_movie")
     */
    public function delete($id)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager -> remove($movie);
        $entityManager -> flush();

        $this->addFlash('success','Filmul a fost sters');

        return $this->redirect($this->generateUrl('index'));
    }

    /**
     * @Route("/info-movie/{id}", name="info_movie")
     */
    public function info($id)
    {
        $movie = $this->getDoctrine()->getRepository(Movie::class)->find($id);

        return $this->render('movie/info.html.twig', [
            'movie' => $movie,
        ]);
    }

}
