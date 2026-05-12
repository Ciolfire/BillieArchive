<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\ContentType;
use App\Entity\Flaw;
use App\Entity\Description;
use App\Form\FlawForm;

use App\Service\DataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("{_locale<%supported_locales%>?%default_locale%}/wiki/flaw")]
class FlawController extends AbstractController
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route("/new/{setting<\w+>}/{parent<\d+>}", name:"flaw_new", methods:["GET", "POST"])]
  public function new(Request $request, ?string $setting = "human", ?Flaw $parent = null): Response
  {
    $type = $this->dataService->findOneBy(ContentType::class, ['name' => $setting]);
    $flaw = new Flaw($type, $parent);

    $form = $this->createForm(FlawForm::class, $flaw);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->save($flaw);

      $this->addFlash('success', ["general.new.done", ['%name%' => $flaw->getName()]]);
      return $this->redirectToRoute('flaw_show', ['id' => $flaw->getId()]);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'flaw',
      'entity' => $flaw,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}", name:"flaw_show", methods:["GET"])]
  public function show(Request $request, Flaw $flaw): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("flaw/$template.html.twig", [
      'flaw' => $flaw,
    ]);
  }

  #[Route("/{id<\d+>}/edit", name:"flaw_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Flaw $flaw): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(FlawForm::class, $flaw);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($flaw);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $flaw->getTitle()]]);
      return $this->redirectToRoute('flaw_show', ['id' => $flaw->getId()]);
    }

    return $this->render('element/new.html.twig', [
      'element' => 'flaw',
      'entity' => $flaw,
      'form' => $form,
    ]);
  }

  #[Route("/{id<\d+>}/delete", name:"flaw_delete", methods:["POST"])]
  public function delete(Request $request, Flaw $flaw): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $token = $request->request->get('_token');
    if ((is_null($token) || is_string($token)) && $this->isCsrfTokenValid('delete' . $flaw->getId(), $token)) {
      $this->dataService->remove($flaw);
      $this->addFlash('success', ["general.delete.done", ['%name%' => $flaw->getName()]]);
    }

    return $this->redirectToRoute('flaw_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("s/{setting}", name: "flaw_index", methods: ["GET"])]
  public function list(?string $setting = null) : Response
  {
    if (is_null($setting)) {
      $contentTypes = $this->dataService->findAll(ContentType::class);
      $contentTypes[] = null;
      $flaws = $this->dataService->findBy(Flaw::class, ['type' => $contentTypes, 'homebrewFor' => null], ['name' => 'ASC']);
      $setting = "human";
    } else {
      $contentType = $this->dataService->findOneBy(ContentType::class, ['name' => $setting]);
      $flaws = $this->dataService->findBy(Flaw::class, ['type' => $contentType], ['name' => 'ASC']);
    }

    return $this->render('flaw/list.html.twig', [
      'setting' => $setting,
      'flaws' => $flaws,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'flaw']),
    ]);
  }
}
