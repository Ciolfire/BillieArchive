<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Character;
use App\Entity\Description;
use App\Entity\Item;
use App\Form\EquipmentType;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Service\DataService;
use App\Service\ItemService;
use Gedmo\Mapping\Driver\Chain;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("{_locale<%supported_locales%>?%default_locale%}/")]
class ItemController extends AbstractController
{
  private DataService $dataService;
  private ItemService $service;
  
  public function __construct(ItemService $service, DataService $dataService)
  {
    $this->service = $service;
    $this->dataService = $dataService;
  }

  #[Route('items', name: 'item_index', methods: ["GET"])]
  public function index(): Response
  {

    return $this->render('item/index.html.twig', [
      'items' => $this->service->getItemList(),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'items']),
    ]);
  }

  #[Route("/list/{type}/{id<\d+>}", name: "item_list", methods: ["GET"])]
  public function list(string $type = null, int $id = null) : Response
  {

    return $this->render('item/index.html.twig', [
      'items' => $this->dataService->getList($type, $id, Item::class, "getItems"),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'item']),
    ]);
  }

  #[Route("item/new", name:"item_new", methods:["GET", "POST"])]
  public function new(Request $request): Response
  {
    $types = $this->service->getTypes();

    $forms = [];
    foreach ($types as $type) {
      $item = new $type[0]();
      $forms[] = $this->createForm($type[1], $item);
    }
    if ($request->isMethod('POST')) {
      foreach ($forms as $form) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $this->service->save($form->getData(), $form->get('img')->getData());
        }
      }

      return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
    }

    $formViews = [];
    foreach ($forms as $form) {
      $formViews[] = $form->createView();
    }
    return $this->render('item/new.html.twig', [
      'items' => $types,
      'forms' => $formViews,
    ]);
  }

  #[Route("item/{id<\d+>}/edit", name:"item_edit", methods:["GET", "POST"])]
  public function edit(Request $request, Item $item): Response
  {
    $form = $this->createForm($this->service->getType($item)[1], $item);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $img = $form->get('img')->getData();
      $path = $this->getParameter('item_img_directory');
      if ($img instanceof UploadedFile && is_string($path)) {
        $item->setImg($this->dataService->upload($img, $path));
      }
      $this->dataService->update($item);
      if ($item->getOwner()) {
        return $this->redirectToRoute('character_show', ['id' => $item->getOwner()->getId(), '_fragment' => 'inventory']);
      }
      return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('item/edit.html.twig', [
      'element' => 'item',
      'entity' => $item,
      'form' => $form,
    ]);
  }

  #[Route("item/{id<\d+>}/drop", name:"item_drop", methods:["GET", "POST"])]
  public function drop(Request $request, Item $item): Response
  {
    $this->denyAccessUnlessGranted('edit', $item->getOwner());

    if ($item->getOwner()->getChronicle()) {
      // Item fall, we setup the chronicle
      $item->setHomebrewFor($item->getOwner()->getChronicle());
      $item->setOwner(null);
      $item->setContainer(null);
    } else {
      // Item cannot fall, destroyed.
      $this->dataService->remove($item);
    }
    $this->dataService->flush();
    if ($request->isXmlHttpRequest()) {
      return new JsonResponse('ok');
    }
    return $this->redirectToRoute('index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route("item/{id<\d+>}/delete", name:"item_delete", methods:["GET", "POST"])]
  public function delete(Request $request, Item $item): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    $token = $request->request->get('_token');
    if ((is_null($token) || is_string($token)) && $this->isCsrfTokenValid('delete' . $item->getId(), $token) || $request->isXmlHttpRequest()) {
      $this->dataService->remove($item);
      $this->dataService->flush();
    }

    return $this->redirectToRoute('item_index', [], Response::HTTP_SEE_OTHER);
  }


  #[Route('item/{item<\d+>}/move/character/{character<\d+>}', name: 'item_move_to_character', methods: ['GET', 'POST'])]
  public function moveItemToCharacter(Request $request, Item $item, ?Character $character): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $item->getOwner());

    if ($request->isXmlHttpRequest()) {
      $item->setContainer(null);
      $item->setOwner($character);
      $this->dataService->flush();

      return new JsonResponse('ok');
    } else {

      return $this->redirectToRoute('index');
    }
  }

  #[Route('item/{item<\d+>}/move/container/{container<\d+>}', name: 'item_move', methods: ['GET', 'POST'])]
  public function moveItem(Request $request, Item $item, ?Item $container): JsonResponse|RedirectResponse
  {
    $this->denyAccessUnlessGranted('edit', $item->getOwner());

    if ($request->isXmlHttpRequest()) {
      if (is_null($container) || $container->isContainer()) {
        $item->setContainer($container);
        $this->dataService->flush();
      }

      return new JsonResponse('ok');
    } else {

      return $this->redirectToRoute('index');
    }
  }
}
