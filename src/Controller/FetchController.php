<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\CharacterDerangement;
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

#[Route('/fetch')]
class FetchController extends AbstractController
{
  private DataService $dataService;
  private TranslatorInterface $translator;


  public function __construct(DataService $dataService, TranslatorInterface $translator)
  {
    $this->dataService = $dataService;
    $this->translator = $translator;
  }


  #[Route('/{_locale<%supported_locales%>?%default_locale%}/load/prerequisites', name: 'a_load_prerequisites', methods: ['POST'])]
  public function loadPrerequisites(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $label = "name";
      if (str_contains($data->value, "App\Entity")) {
        // Set the context
        if ($data->homebrew) {
          $filters = ['homebrewFor' => [null, "{$data->homebrew}"]];
        } else {
          $filters = ['homebrewFor' => null];
        }
        // The choice is an entity, we get them
        switch ($data->value) {
          case "App\Entity\Clan":
            $choices = $this->dataService->findBy($data->value, $filters, ['isBloodline' => 'ASC', 'name' => 'ASC']);
            break;
          case "App\Entity\Attribute":
          case "App\Entity\Skill":
            $choices = $this->dataService->findBy($data->value, [], ['name' => 'ASC']);
            break;
          case "App\Entity\Merit":
            $label = "detailedName";
          default:
            $choices = $this->dataService->findBy($data->value, $filters, ['name' => 'ASC']);
            break;
        }
      } else {
        $choices = [];
      }

      $choices = $this->render('forms/choices.html.twig', [
        'choices' => $choices,
        'label' => $label,
      ])->getContent();
      return new JsonResponse(['choices' => $choices]);
    } else {
      return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
    }
  }

  #[Route('/{_locale<%supported_locales%>?%default_locale%}/load/test', name: 'a_load_test', methods: ['GET'])]
  public function loadTest(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      return new JsonResponse([
        'choices' => "ok",
        'methods' => "ok",
      ]);
    }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/load/removable', name: 'a_load_removable', methods: ['GET', 'POST'])]
  public function loadRemovable(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $character = $this->dataService->find(Character::class, $data->character);

      if (!$character instanceof Character) {
        return new JsonResponse();
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

      $label = "name";
      switch ($data->type) {
        case "attribute":
          $attributes = $character->getPositiveAttributes();
          /** @var AttributeRepository */
          $repo = $this->dataService->getDoctrine()->getRepository(Attribute::class);
          $choices = $repo->filterByIdentifiers($attributes);
          $identifier = 'identifier';
          break;
        case "skill":
          $skills = $character->getLearnedSkills();
          /** @var SkillRepository */
          $repo = $this->dataService->getDoctrine()->getRepository(Skill::class);
          $choices = $repo->filterByIdentifiers($skills);
          $identifier = 'identifier';
          break;
        case 'merit':
          $choices = $character->getMerits();
          $identifier = "id";
          $label = "detailedName";
          break;
        case "specialty":
          $choices = $character->getSpecialties();
          $identifier = 'id';
          $label = "detailedName";
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
          $label = "detailedName";
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
    }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
  }

  
  #[Route('/load/status', name: 'a_load_status', methods: ['GET', 'POST'])]
  public function loadStatus(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());
      $character = $this->dataService->find(Character::class, $data->character);

      if (!$character instanceof Character) {
        return new JsonResponse();
      }

      $label = "name";
      switch ($data->type) {
        case "willpower":
        case "potency":
          break;
        case "attribute":
          $attributes = $character->getPositiveAttributes();
          /** @var AttributeRepository */
          $repo = $this->dataService->getDoctrine()->getRepository(Attribute::class);
          $choices = $repo->filterByIdentifiers($attributes);
          $identifier = 'identifier';
          break;
        case "skill":
          $skills = $character->getLearnedSkills();
          /** @var SkillRepository */
          $repo = $this->dataService->getDoctrine()->getRepository(Skill::class);
          $choices = $repo->filterByIdentifiers($skills);
          $identifier = 'identifier';
          break;
        case 'merit':
          $choices = $character->getMerits();
          $identifier = "id";
          $label = "detailedName";
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
      return new JsonResponse([
        'choices' => $choices,
      ]);
    }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{_locale<%supported_locales%>?%default_locale%}/entity/form', name: 'fetch_entity_form', methods: ['POST'])]
  public function fetchEntityForm(Request $request): JsonResponse|RedirectResponse
  {
    if ($request->isXmlHttpRequest()) {
      $data = json_decode($request->getContent());

      $form = $data->entity::getForm();
      if ($form) {
        return new JsonResponse([
          'data' => $this->render('_form.html.twig', ['form' => $this->createForm($form)])->getContent(),
        ]);
      }

      return new JsonResponse(status:204);
    }

    return $this->redirectToRoute('character_index', [], Response::HTTP_SEE_OTHER);
  }
}