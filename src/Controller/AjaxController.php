<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\CharacterDerangement;
use App\Entity\CharacterMerit;
use App\Entity\CharacterSpecialty;
use App\Entity\Clan;
use App\Entity\Ghoul;
use App\Entity\Skill;
use App\Entity\Vampire;
use App\Repository\AttributeRepository;
use App\Repository\SkillRepository;
use App\Service\DataService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/ajax')]
class AjaxController extends AbstractController
{
  private DataService $dataService;
  private TranslatorInterface $translator;


  public function __construct(DataService $dataService, TranslatorInterface $translator)
  {
    $this->dataService = $dataService;
    $this->translator = $translator;
  }


  #[Route('/{_locale<%supported_locales%>?%default_locale%}/load/prerequisites', name: 'a_load_prerequisites', methods: ['GET', 'POST'])]
  public function loadPrerequisites(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      switch ($data->value) {
        case "App\Entity\Clan":
          $choices = $this->dataService->findBy($data->value, [], ['isBloodline' => 'ASC', 'name' => 'ASC']);
          break;

        default:
          $choices = $this->dataService->findBy($data->value, [], ['name' => 'ASC']);
          break;
      }

      $choices = $this->render('forms/choices.html.twig', [
        'choices' => $choices,
      ])->getContent();
      return new JsonResponse(['choices' => $choices]);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/load/removable', name: 'a_load_removable', methods: ['GET', 'POST'])]
  public function loadRemovable(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $character = $this->dataService->find(Character::class, $data->character);

      if (!$character instanceof Character) {
        return null;
      }
      $methods = [
        0 => [
          'id' => 'reduce',
          'name' => $this->translator->trans('action.reduce', [], 'app'),
        ],
        1 => [
          'id' => 'remove',
          'name' => $this->translator->trans('action.remove', [], 'app'),
        ],
      ];

      switch ($data->type) {
        case "attribute":
          $attributes = $character->getPositiveAttributes();
          /** @var AttributeRepository */
          $repo = $this->dataService->getDoctrine()->getRepository(Attribute::class);
          $choices = $repo->filterByIdentifiers($attributes);
          $identifier = 'identifier';
          $label = 'name';
          break;
        case "skill":
          $skills = $character->getLearnedSkills();
          /** @var SkillRepository */
          $repo = $this->dataService->getDoctrine()->getRepository(Skill::class);
          $choices = $repo->filterByIdentifiers($skills);
          $identifier = 'identifier';
          $label = 'name';
          break;
        case "merit":
          $choices = $character->getMerits();
          $identifier = 'id';
          $label = 'name';
          break;
        case "specialty":
          $choices = $character->getSpecialties();
          $identifier = 'id';
          $label = 'name';
          unset($methods[0]);
          break;
        case "willpower":
        case "potency":
          unset($methods[1]);
          break;
        case "derangement":
          $choices = $this->dataService->findBy(CharacterDerangement::class, ['character' => $character, 'moralityLink' => null]);
          $identifier = 'id';
          $label = 'derangement';
          break;
        case "devotion":
          if ($character instanceof Vampire) {
            $choices = $character->getDevotions();
          } else {
            $lesser = $character->getLesserTemplate();
            if ($lesser instanceof Ghoul) {
              $choices = $lesser->getDevotions();
            }
          }
          unset($methods[0]);
          $identifier = 'id';
          $label = 'name';
          break;
        case "discipline":
          if ($character instanceof Vampire) {
            $choices = $character->getDisciplines();
          } else {
            $lesser = $character->getLesserTemplate();
            if ($lesser instanceof Ghoul) {
              $choices = $lesser->getDisciplines();
            }
          }
          $identifier = 'id';
          $label = 'name';
          break;
          break;
        case "ritual":
          break;
        default:
          $choices = [];
          break;
      }

      if (isset($choices)) {
        $choices = $this->render('forms/choices.html.twig', [
          'choices' => $choices,
          'id' => $identifier,
          'label' => $label,
        ])->getContent();
      } else {
        $choices = null;
      }
      $methods = $this->render('forms/choices.html.twig', [
        'hasEmpty' => false,
        'choices' => $methods,
      ])->getContent();
      return new JsonResponse([
        'choices' => $choices,
        'methods' => $methods,
      ]);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }
}