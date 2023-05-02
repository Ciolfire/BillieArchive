<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Attribute;
use App\Entity\Character;
use App\Entity\CharacterMerit;
use App\Entity\CharacterSpecialty;
use App\Entity\Clan;
use App\Entity\Skill;
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


  #[Route('/load/prerequisites', name: 'a_load_prerequisites', methods: ['GET', 'POST'])]
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
          $choices = $this->dataService->findBy(CharacterMerit::class, ['character' => $character]);
          $identifier = 'id';
          $label = 'name';
          break;
        case "specialty":
          $choices = $this->dataService->findBy(CharacterSpecialty::class, ['character' => $character]);
          $identifier = 'id';
          $label = 'name';
          unset($methods[0]);
          break;
        case "willpower":
          unset($methods[1]);
          break;
        default:
          $choices = [];
          break;
      }

      if (isset($choices)) {
        $choices = $this->render('forms/choices.html.twig', [
          'choices' => $choices,
          'id' => $identifier,
          'label' => 'name',
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