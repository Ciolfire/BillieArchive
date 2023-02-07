<?php

namespace App\Controller\Vampire;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\User;
use App\Entity\Vampire;
use App\Entity\Clan;
use App\Entity\Description;
use App\Entity\Discipline;
use App\Entity\DisciplinePower;
use App\Form\ClanType;
use App\Form\DisciplinePowerType;
use App\Form\DisciplineType;
use App\Form\EmbraceType;
use App\Repository\CharacterRepository;
use App\Service\DataService;
use App\Service\VampireService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/{_locale<%supported_locales%>?%default_locale%}/vampire')]
class VampireController extends AbstractController
{
  private $dataService;
  private $service;

  public function __construct(DataService $dataService, VampireService $service)
  {
    $this->dataService = $dataService;
    $this->service = $service;
  }

  #[Route('/', name: 'vampire_index', methods: ['GET'])]
  public function vampires(CharacterRepository $characterRepository): Response
  {
    /** @var User $user */
    $user = $this->getUser();
    return $this->render('character/index.html.twig', [
      'characters' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => false
      ]),
      'npc' => $characterRepository->findBy([
        'player' => $user->getId(),
        'isNpc' => true
      ]),
    ]);
  }

  #[Route('/{id<\d+>}/embrace', name: 'character_embrace', methods: ['GET', 'POST'])]
  public function embrace(Request $request, Character $character): Response
  {
    $clans = $this->dataService->findBy(Clan::class, ['isBloodline' => false]);
    $attributes = $this->dataService->findAll(Attribute::class);
    $disciplines = $this->dataService->findAll(Discipline::class);
    $form = $this->createForm(EmbraceType::class, null, ['clans' => $clans, 'attributes' => $attributes]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
      $this->service->embrace($character, $form);
      return $this->redirectToRoute('character_show', ['id' => $character->getId()]);
    }
    return $this->render('vampire/embrace/sheet.html.twig', [
      'character' => $character,
      'clans' => $clans,
      'disciplines' => $disciplines,
      'form' => $form,
      'type' => "vampire",
    ]);
  }
}
