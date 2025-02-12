<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Covenant;
use App\Entity\Description;
use App\Entity\MageOrder;
use App\Entity\Organization;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}/organization')]
class OrganizationController extends AbstractController
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route('/list/{setting}', name: 'organization_index', methods: ['GET'])]
  public function organizations(string $setting = null): Response
  {
    $organizations = $this->dataService->getOrganizations($setting);

    if ($setting == null) {
      $setting = "human";
    }

    switch ($setting) {
      case 'vampire':
        $type = "covenant";
        break;
      case 'mage':
        $type = "order";
        break;
      default:
        $type = "organization";
      break;
    }

    return $this->render('organization/index.html.twig', [
      'organizations' => $organizations,
      'type' => $type,
      'setting' => $setting,
      'description' => $this->dataService->findOneBy(Description::class, ['name' => $type]),
    ]);
  }

  #[Route("/list/{filter<\w+>}/{id<\d+>}/{setting<\w+>}", name: "organization_list", methods: ["GET"])]
  public function organizationList(string $filter, string $setting, int $id): Response
  {
    $result = $this->dataService->getItemFromType($filter, $id);

    switch ($setting) {
      case 'vampire':
        $type = "covenant";
        $organizations = $result['item']->getCovenants();
        break;
      case 'mage':
        $type = "order";
        $organizations = $result['item']->getOrders();
        break;
        default:
        $type= "organization";
        $organizations = $result['item']->getOrganizations();
        break;
    }

    return $this->render("organization/index.html.twig", [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'organization']),
      'organizations' => $organizations,
      'item' => [
        'filter' => $filter,
        'id' => $id,
      ],
      'type' => $type,
      'setting' => $setting,
      'search' => [],
      'back' => $result['back'],
    ]);
  }


  // #[Route('/organization/{id<\d+>}', name: 'organization_show', methods: ['GET'])]
  // public function organizationShow(Organization $clan): Response
  // {
  //   return $this->render('mage/organization/show.html.twig', [
  //     'organization' => $clan,
  //   ]);
  // }

  #[Route('/{id<\d+>}', name: 'organization_show', methods: ['GET'])]
  public function show(Organization $organization): Response
  {
    return $this->render('organization/show.html.twig', [
      'organization' => $organization,
      'setting' => $organization->getSetting(),
    ]);
  }

  #[Route('/new/{setting<\w+>}/{type<\w+>}/{id<\w+>}', name: 'organization_new', methods: ['GET', 'POST'])]
  public function new(Request $request, $setting = null, $type = null, $id = null): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $item = null;
    if ($type && $id) {
      $result = $this->dataService->getItemFromType($type, $id);
      $item = $result['item'];
    }

    switch ($setting) {
      case 'vampire':
        $organization = new Covenant();
        break;
      case 'mage':
        $organization = new MageOrder();
        break;
      default:
        $organization = new Organization();
        break;
    }
    $form = $this->createForm($organization->getForm(), $organization, ['item' => $item]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $fileOrganization = $this->getParameter('organizations_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($fileOrganization)) {
        $organization->setEmblem($this->dataService->upload($emblem, $fileOrganization));
      }
      $this->dataService->save($organization);

      $this->addFlash('success', ["general.new.done", ['%name%' => $organization->getName()]]);
      if ($item == null) {
        return $this->redirectToRoute('organization_index', ['setting' => $setting, '_fragment' => $organization->getName()]);
      }
      return $this->redirectToRoute('organization_list', ['setting' => $setting, 'filter' => $type , 'id' => $id,  '_fragment' => $organization->getName()]);
    }

    if ($setting) {
      return $this->render("{$setting}/organization/form.html.twig", [
        'form' => $form,
      ]);
    }

    return $this->render('organization/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/{setting<\w+>}/{id<\d+>}/edit', name: 'organization_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Organization $organization, $setting = "human"): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');
    $form = $this->createForm($organization->getForm(), $organization);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $fileOrganization = $this->getParameter('organizations_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($fileOrganization)) {
        $organization->setEmblem($this->dataService->upload($emblem, $fileOrganization));
      }
      $this->dataService->update($organization);
      $this->addFlash('success', ["general.edit.done", ['%name%' => $organization->getName()]]);
      return $this->redirectToRoute('organization_index', ['setting' => $setting, '_fragment' => $organization->getName()]);
    }

    if ($setting) {
      return $this->render("{$setting}/organization/form.html.twig", [
        'form' => $form,
      ]);
    }

    return $this->render('organization/form.html.twig', [
      'form' => $form,
      'setting' => $setting,
    ]);
  }
}
