<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Chronicle;
use App\Entity\Covenant;
use App\Entity\Description;
use App\Entity\Organization;
use App\Form\CovenantType;
use App\Form\OrganizationType;
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

  #[Route('/{setting}', name: 'organization_index', methods: ['GET'])]
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

  #[Route("/{filter<\w+>}/{id<\d+>}", name: "organization_list", methods: ["GET"])]
  public function organizationList(string $filter, int $id): Response
  {
    // $dataService->getList()

    // switch ($filter) {
    //   case 'chronicle':
    //     /** @var Chronicle */
    //     $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
    //     $back = ['organization' => 'homebrew_index', 'id' => $id];
    //     break;
    //   case 'book':
    //   default:
    //     /** @var Book */
    //     $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
    //     $back = ['organization' => 'book_index', 'id' => $id];
    // }

    return $this->render('mage/organization/index.html.twig', [
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'organization']),
      'organizations' => $item->getOrganizations(),
      'search' => [],
      'back' => $back,
    ]);
  }


  // #[Route('/organization/{id<\d+>}', name: 'organization_show', methods: ['GET'])]
  // public function organizationShow(Organization $clan): Response
  // {
  //   return $this->render('mage/organization/show.html.twig', [
  //     'organization' => $clan,
  //   ]);
  // }

  #[Route('/{setting<\w+>}/new', name: 'organization_new', methods: ['GET', 'POST'])]
  public function new(Request $request, $setting = null): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    switch ($setting) {
      case 'vampire':
        $organization = new Covenant();
        $form = $this->createForm(CovenantType::class, $organization);
        break;

      default:
        $organization = new Organization();
        $form = $this->createForm(OrganizationType::class, $organization);
        break;
    }

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $fileOrganization = $this->getParameter('organizations_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($fileOrganization)) {
        $organization->setEmblem($this->dataService->upload($emblem, $fileOrganization));
      }
      $this->dataService->save($organization);

      $this->addFlash('success', ["general.new.done", ['%name%' => $organization->getName()]]);
      return $this->redirectToRoute('organization_index', ['setting' => $setting, '_fragment' => $organization->getName()]);
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

    switch ($setting) {
      case 'vampire':
        $form = $this->createForm(CovenantType::class, $organization);
        break;

      default:
        $form = $this->createForm(OrganizationType::class, $organization);
        break;
    }

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
