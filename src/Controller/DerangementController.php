<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Derangement;
use App\Entity\Description;
use App\Form\DerangementForm;
use App\Repository\DerangementRepository;
use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("{_locale<%supported_locales%>?%default_locale%}/wiki/derangement")]
class DerangementController extends AbstractController
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("s", name: "derangement_index", methods: ["GET"])]
  public function index() : Response
  {
    // Should move the logic in service, so that this is no longer a redirect :)
    return $this->redirectToRoute('derangement_list', ['type' => null, 'id' => null]);
  }

  #[Route("/list/{filter<\w+>}/{id<\w+>}", name: "derangement_list", methods: ["GET"])]
  public function list(?string $filter = null, ?int $id = null) : Response
  {
    /** @var DerangementRepository $repo */
    $repo = $this->dataService->getRepository(Derangement::class);

    switch ($filter) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        $list = $item->getDerangements();
        $derangements = [];
        foreach ($list as $derangement) {
          // We only get the mild derangements, we can get all the severe from them
          /** @var Derangement $derangement */
          if (!is_null($derangement->getPreviousAilment())) {
            $prevId = $derangement->getPreviousAilment()->getId();
            $derangements[$prevId] = $derangement->getPreviousAilment();
          } else {
            $derangements[$derangement->getId()] = $derangement;
          }
        }
        break;
      default:
        $derangements = $repo->findMild();
        break;
    }

    return $this->render('derangement/list.html.twig', [
      'derangements' => $derangements,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'derangement']),
      // 'search' => $search, // Kinda want to replace for dynamic list
    ]);
  }

  #[Route("/new", name:"derangement_new", methods:["GET", "POST"])]
  public function new(Request $request): Response
  {
    $derangement = new Derangement();

    $form = $this->createForm(DerangementForm::class, $derangement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($derangement);

      $this->addFlash('success', ["general.new.done", ['%name%' => $derangement->getName()]]);
      return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name:"derangement_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Derangement $derangement, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(DerangementForm::class, $derangement);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($derangement);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $derangement->getName()]]);
      return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name:"derangement_show", methods:["GET"])]
  public function show(Derangement $derangement): Response
  {
    return $this->render('element/show.html.twig', [
      'element' => 'derangement',
      'entity' => $derangement,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name:"derangement_delete", methods:["POST"])]
  public function delete(Request $request, Derangement $derangement, EntityManagerInterface $entityManager): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $token = $request->request->get('_token');
    if ((is_null($token) || is_string($token)) && $this->isCsrfTokenValid('delete' . $derangement->getId(), $token)) {
      $entityManager->remove($derangement);
      $entityManager->flush();
    }

    $this->addFlash('success', ["general.edit.delete", ['%name%' => $derangement->getName()]]);
    return $this->redirectToRoute('derangement_list', [], Response::HTTP_SEE_OTHER);
  }
}
