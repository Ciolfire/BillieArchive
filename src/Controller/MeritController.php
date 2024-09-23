<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\ContentType;
use App\Entity\Description;
use App\Entity\Merit;
use App\Form\MeritType;

use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("{_locale<%supported_locales%>?%default_locale%}/wiki/merit")]
class MeritController extends AbstractController
{
  private DataService $dataService;
  /** @var array<string> */
  private array $categories = ['mental', 'physical', 'social'];

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/list/{type}/{id}", name: "merit_list", methods: ["GET"])]
  public function list(string $type = null, int|string $id = null) : Response
  {
    $chronicle = false;
    $search = ['category' => $this->categories];
    switch ($type) {
      case 'book':
        /** @var Book */
        $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        if ($item instanceof Book) {
          $setting = $item->getSetting();
          $merits = $item->getMerits();
          // We get the type of book/item for the search filters
          $types = $this->dataService->getMeritTypes($item);
          if (count($types) > 1) {
            $search['type'] = $types;
          }
        }
        break;

      case 'chronicle':
        /** @var Chronicle */
        $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
        if ($item instanceof Chronicle) {
          $chronicle = $id;
          $setting = $item->getType();
          $merits = $item->getMerits();
        }
        break;
      case 'type':
        switch ($id) {
          case 'ghoul':
            $setting = 'vampire';
            break;
          default:
            $setting = $id;
            break;
        }
        if (!isset($contentTypes)) {
          $contentTypes = $this->dataService->findBy(ContentType::class, ['name' => $id]);
        }
        $merits = $this->dataService->findBy(Merit::class, ['type' => $contentTypes, 'homebrewFor' => null]);
        break;

      default:
        $merits = $this->dataService->findBy(Merit::class, ['homebrewFor' => null], ['name' => 'ASC']);
        $search['type'] = $this->dataService->getMeritTypes();
        $setting = "human";
        break;
    }
    /** @var Merit $merit */
    foreach ($merits as $merit) {
      $this->dataService->loadPrerequisites($merit);
    }

    return $this->render('merit/list.html.twig', [
      'setting' => $setting,
      'merits' => $merits,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'merit']),
      'search' => $search, // Kinda want to replace for dynamic list
      'chronicle' => $chronicle,
    ]);
  }

  #[Route("/new", name: "merit_new", methods: ["GET", "POST"])]
  public function new(Request $request): Response
  {
    $merit = new Merit();

    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->add($merit);
      $this->dataService->flush();

      $this->addFlash('success', ["general.new.done", ['%name%' => $merit->getName()]]);
      return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('merit/new.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name: "merit_show", methods: ["GET"])]
  public function show(Merit $merit): Response
  {
    $this->dataService->loadPrerequisites($merit);

    return $this->render('merit/show.html.twig', [
      'merit' => $merit,
    ]);
  }

  #[IsGranted('ROLE_ST')]
  #[Route("/{id<\d+>}/edit", name: "merit_edit", methods: ["GET", "POST"])]
  public function edit(Request $request, Merit $merit): Response
  {
    $form = $this->createForm(MeritType::class, $merit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($merit);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $merit->getName()]]);
      return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('merit/edit.html.twig', [
      'merit' => $merit,
      'form' => $form,
    ]);
  }

  #[IsGranted('ROLE_ST')]
  #[Route("/{id<\d+>}/delete", name: "merit_delete", methods: ["POST"])]
  public function delete(Request $request, Merit $merit): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $token = $request->request->get('_token');
    if ((is_null($token) || is_string($token)) && $this->isCsrfTokenValid('delete' . $merit->getId(), $token)) {
      $this->dataService->remove($merit);
      $this->dataService->flush();
      $this->addFlash('success', ["general.delete.done", ['%name%' => $merit->getName()]]);
    }

    return $this->redirectToRoute('merit_list', [], Response::HTTP_SEE_OTHER);
  }
}
