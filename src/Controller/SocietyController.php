<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chronicle;
use App\Entity\Society;
use App\Form\SocietyType;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale<%supported_locales%>?%default_locale%}/society/")]
class SocietyController extends AbstractController
{
  private DataService $dataService;
  // private CharacterService $service;

  public function __construct(DataService $dataService/*, CharacterService $service*/)
  {
    $this->dataService = $dataService;
    // $this->service = $service;
  }

  #[Route("new", name: "society_new")]
  public function new(Request $request, ?Chronicle $chronicle) : Response
  {
    $society = new Society($chronicle);
    $form = $this->createForm(SocietyType::class, $society);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($society);
      $this->addFlash('notice', "{$society->getName()} created");
      return $this->redirectToRoute('society_show', ['id' => $society->getId()]);
    }

    return $this->render('society/new.html.twig', [
      'chronicle' => $society,
      'form' => $form,
    ]);
  }

  #[Route("new/{chronicle<\d+>}", name: "society_chronicle_new")]
  public function newForChronicle(Request $request, ?Chronicle $chronicle) : Response
  {
    $society = new Society($chronicle);
    $form = $this->createForm(SocietyType::class, $society);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($society);
      $this->addFlash('notice', "{$society->getName()} created");
      return $this->redirectToRoute('society_show', ['id' => $society->getId()]);
    }

    return $this->render('society/new.html.twig', [
      'chronicle' => $society,
      'form' => $form,
    ]);
  }

  #[Route("{id<\d+>}/edit", name: "society_edit")]
  public function edit(Request $request, Society $society) : Response
  {
    $form = $this->createForm(SocietyType::class, $society);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->flush();
      $this->addFlash('notice', "{$society->getName()} updated");
      return $this->redirectToRoute('society_show', ['id' => $society->getId()]);
    }

    return $this->render('society/new.html.twig', [
      'chronicle' => $society,
      'form' => $form,
    ]);
  }

  #[Route("{id<\d+>}", name: "society_show", methods: ["GET"])]
  public function show(Society $society) : Response
  {
    $setting = $society->getSetting();
    if (is_null($setting)) {
      $setting = 'human';
    }

    return $this->render('society/show.html.twig', [
      'society' => $society,
      'setting' => $setting,
    ]);
  }

  // #[IsGranted('ROLE_ST')]
  #[Route("/{id<\d+>}/delete", name: "society_delete", methods: ["POST"])]
  public function delete(Request $request, Society $society): Response
  {
    $chronicle = $society->getChronicle();
    if ($this->getUser() === $society->getChronicle()->getStoryteller()) {

      $token = $request->request->get('_token');
      if ((is_null($token) || is_string($token)) && $this->isCsrfTokenValid('delete' . $society->getId(), $token)) {
        $this->addFlash('notice', "{$society->getName()} deleted");
        $this->dataService->remove($society);
      }
    }

    return $this->redirectToRoute('chronicle_society_index', ['id' => $chronicle->getId()], Response::HTTP_SEE_OTHER);
  }

  #[Route("chronicle/{id<\d+>}/", name: "chronicle_society_index", methods: ["GET"])]
  public function index(Chronicle $chronicle) : Response
  {
    return $this->render('chronicle/societies/index.html.twig', [
      'chronicle' => $chronicle,
      'setting' => $chronicle->getType(),
    ]);
  }

  #[Route("{id<\d+>}/add/character", name: "society_add_character", methods: ["GET", "POST"])]
  public function characterAdd(Request $request, Society $society) : Response
  {
    $form = $this->createForm(SocietyType::class, $society, ['add_character' => true, 'path' => $this->getParameter('characters_direct_directory')]);
    
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($society);
      $this->addFlash('notice', "Members updated");
      return $this->redirectToRoute('society_show', ['id' => $society->getId()]);
    }

    $setting = $society->getSetting();
    if (is_null($setting)) {
      $setting = 'human';
    }

    return $this->render('society/add_character.html.twig', [
      'form' => $form,
      'setting' => $setting,
    ]);
  }
}