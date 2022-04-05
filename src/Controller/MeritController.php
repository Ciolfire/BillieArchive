<?php

namespace App\Controller;

use App\Entity\Merit;
use App\Form\MeritType;
use App\Repository\MeritRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale<%supported_locales%>?%default_locale%}/merit")
 */
class MeritController extends AbstractController
{
    /**
     * @Route("/", name="merit_index", methods={"GET"})
     */
    public function index(MeritRepository $meritRepository): Response
    {
        return $this->render('merit/index.html.twig', [
            'merits' => $meritRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="merit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $merit = new Merit();
        $form = $this->createForm(MeritType::class, $merit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($merit);
            $entityManager->flush();

            return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('merit/new.html.twig', [
            'merit' => $merit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="merit_show", methods={"GET"})
     */
    public function show(Merit $merit): Response
    {
        return $this->render('merit/show.html.twig', [
            'merit' => $merit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="merit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Merit $merit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeritType::class, $merit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('merit/edit.html.twig', [
            'merit' => $merit,
            'form' => $form,
        ]);
    }
    
    /**
     * @Route("/{id}/translate/{language}", name="merit_translate", methods={"GET", "POST"})
     */
    public function translate(Request $request, Merit $merit, $language, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeritType::class, $merit);
        $form->handleRequest($request);
        $merit->setTranslatableLocale($language); // change locale
        // dd($merit);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($merit);
            $entityManager->flush();

            return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('merit/edit.html.twig', [
            'merit' => $merit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="merit_delete", methods={"POST"})
     */
    public function delete(Request $request, Merit $merit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$merit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($merit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('merit_index', [], Response::HTTP_SEE_OTHER);
    }
}
