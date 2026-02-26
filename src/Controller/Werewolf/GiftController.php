<?php

namespace App\Controller\Werewolf;

use App\Entity\Description;
use App\Entity\Gift;
use App\Entity\GiftList;
use App\Entity\Rite;
use App\Form\Werewolf\GiftForm;
use App\Form\Werewolf\GiftListForm;
use App\Form\Werewolf\RiteForm;
use App\Service\DataService;
use App\Service\WerewolfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/werewolf')]
final class GiftController extends AbstractController
{
  private DataService $dataService;
  private WerewolfService $service;

  public function __construct(DataService $dataService, WerewolfService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/wiki/gifts', name: 'werewolf_gift_index', methods: ['GET'])]
  public function gifts(): Response
  {
    return $this->render('werewolf/gift/list.html.twig', [
      'giftLists' => $this->dataService->findBy(GiftList::class, [
        'homebrewFor' => null,
      ]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'gift']),
    ]);
  }

  #[Route("/wiki/gifts/list/{filter<\w+>}/{id<\w+>}", name: "werewolf_gift_list", methods: ["GET"])]
  public function pathList(string $filter, int $id): Response
  {
    $gifts = $this->dataService->getList($filter, $id, GiftList::class, 'getPaths');

    return $this->render('werewolf/gift/list.html.twig', [
      'gifts' => $gifts,
      'filter' => $filter,
      'id' => $id,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'path']),
    ]);
  }

  // GIFT LIST

  #[Route('/wiki/gift/list/{id<\d+>}', name: 'werewolf_gift_list_show', methods: ['GET'])]
  public function giftList(Request $request, GiftList $giftList): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("werewolf/gift/list/$template.html.twig", [
      'giftList' => $giftList,
    ]);
  }

  #[Route('/gift/list/new', name: 'werewolf_gift_list_new', methods: ['GET', 'POST'])]
  public function giftListNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $gift = new GiftList();
    // $gift = new Gift($bloodline, $this->dataService->getItem($request->request->get('filter'), $request->request->get('id')), $ancient);
    $form = $this->createForm(GiftListForm::class, $gift);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->save($gift);

      $this->addFlash('success', ["general.new.done", ['%name%' => $gift->getName()]]);

      return $this->redirectToRoute('werewolf_gift_index', ['_fragment' => $gift->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/gift/list/{id<\d+>}/edit', name: 'werewolf_gift_list_edit', methods: ['GET', 'POST'])]
  public function giftListEdit(Request $request, GiftList $giftList): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(GiftListForm::class, $giftList);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $this->dataService->update($giftList);

      $this->addFlash('success', ["general.edit.done", ['%name%' => $giftList->getName()]]);
      return $this->redirectToRoute('werewolf_gift_list_show', ['id' => $giftList->getId()]);
    }

    return $this->render('werewolf/form.html.twig', [
      'action' => 'edit',
      'form' => $form,
    ]);
  }

  // GIFTS

  #[Route('/wiki/gift/{id<\d+>}', name: 'werewolf_gift_show', methods: ['GET'])]
  public function gift(Request $request, Gift $gift): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("werewolf/gift/$template.html.twig", [
      'gift' => $gift,
    ]);
  }

  #[Route('/gift/{list<\d+>}/new', name: 'werewolf_gift_new', methods: ['GET', 'POST'])]
  public function giftNew(Request $request, GiftList $list): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $gift = new Gift(list: $list);
    $form = $this->createForm(GiftForm::class, $gift);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->save($gift);

      $this->addFlash('success', ["general.new.done", ['%name%' => $gift->getName()]]);

      return $this->redirectToRoute('werewolf_gift_index', ['_fragment' => $gift->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/gift/{id<\d+>}/edit', name: 'werewolf_gift_edit', methods: ['GET', 'POST'])]
  public function giftEdit(Request $request, Gift $gift): Response {
    $this->denyAccessUnlessGranted('ROLE_ST');
    $form = $this->createForm(GiftForm::class, $gift);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->save($gift);

      $this->addFlash('success', ["general.new.done", ['%name%' => $gift->getName()]]);

      return $this->redirectToRoute('werewolf_gift_list_show', ['id' => $gift->getList()->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/form.html.twig', [
      'form' => $form,
    ]);
  }

  // RITES
  #[Route('/wiki/rites', name: 'werewolf_rite_index', methods: ['GET'])]
  public function rites(): Response
  {
    return $this->render('werewolf/rite/list.html.twig', [
      'rites' => $this->dataService->findBy(Rite::class, [
        'homebrewFor' => null,
      ], [
        'level' => 'ASC',
      ]),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'rite']),
    ]);
  }

  #[Route('/wiki/rite/{id<\d+>}', name: 'werewolf_rite_show', methods: ['GET'])]
  public function rite(Request $request, Gift $rite): Response
  {
    $template = "show";
    if ($request->isXmlHttpRequest()) {
      $template = "_show";
    }

    return $this->render("werewolf/rite/$template.html.twig", [
      'rite' => $rite,
    ]);
  }

  #[Route('/rite/new', name: 'werewolf_rite_new', methods: ['GET', 'POST'])]
  public function riteNew(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $rite = new Rite();
    $form = $this->createForm(RiteForm::class, $rite);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->save($rite);

      $this->addFlash('success', ["general.new.done", ['%name%' => $rite->getName()]]);

      return $this->redirectToRoute('werewolf_rite_index', ['_fragment' => $rite->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/rite/{id<\d+>}/edit', name: 'werewolf_rite_edit', methods: ['GET', 'POST'])]
  public function riteEdit(Request $request, Rite $rite): Response {
    $this->denyAccessUnlessGranted('ROLE_ST');
    $form = $this->createForm(RiteForm::class, $rite);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $this->dataService->save($rite);

      $this->addFlash('success', ["general.new.done", ['%name%' => $rite->getName()]]);

      return $this->redirectToRoute('werewolf_rite_index', ['_fragment' => $rite->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('werewolf/form.html.twig', [
      'form' => $form,
    ]);
  }
}
