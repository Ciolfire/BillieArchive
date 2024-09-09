<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Description;
use App\Entity\Organization;
use App\Form\OrganizationType;
use App\Service\DataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale<%supported_locales%>?%default_locale%}')]
class OrganizationController extends AbstractController
{
  private DataService $dataService;

  public function __construct(DataService $dataService)
  {
    $this->dataService = $dataService;
  }

  #[Route('/organizations', name: 'organization_index', methods: ['GET'])]
  public function organizations(): Response
  {
    return $this->render('organization/index.html.twig', [
      'organizations' => $this->dataService->findAll(Organization::class),
      'description' => $this->dataService->findOneBy(Description::class, ['name' => 'organization']),
    ]);
  }

  // #[Route("/organization/{filter<\w+>}/{id<\d+>}", name: "organization_list", methods: ["GET"])]
  // public function organizationList(string $filter, int $id): Response
  // {
  //   switch ($filter) {
  //     case 'chronicle':
  //       /** @var Chronicle */
  //       $item = $this->dataService->findOneBy(Chronicle::class, ['id' => $id]);
  //       $back = ['organization' => 'homebrew_index', 'id' => $id];
  //       break;
  //     case 'book':
  //     default:
  //       /** @var Book */
  //       $item = $this->dataService->findOneBy(Book::class, ['id' => $id]);
  //       $back = ['organization' => 'book_index', 'id' => $id];
  //   }

  //   return $this->render('mage/organization/index.html.twig', [
  //     'description' => $this->dataService->findOneBy(Description::class, ['name' => 'organization']),
  //     'organizations' => $item->getOrganizations(),
  //     'search' => [],
  //     'back' => $back,
  //   ]);
  // }


  // #[Route('/organization/{id<\d+>}', name: 'organization_show', methods: ['GET'])]
  // public function organizationShow(Organization $clan): Response
  // {
  //   return $this->render('mage/organization/show.html.twig', [
  //     'organization' => $clan,
  //   ]);
  // }

  #[Route('/organization/new', name: 'organization_new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $organization = new Organization();
    $form = $this->createForm(OrganizationType::class, $organization);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $fileOrganization = $this->getParameter('organizations_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($fileOrganization)) {
        $organization->setEmblem($this->dataService->upload($emblem, $fileOrganization));
      }
      $this->dataService->save($organization);

      return $this->redirectToRoute('organization_index', ['_fragment' => $organization->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('organization/form.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/organization/{id<\d+>}/edit', name: 'organization_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Organization $organization): Response
  {
    $this->denyAccessUnlessGranted('ROLE_ST');

    $form = $this->createForm(OrganizationType::class, $organization);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $emblem = $form->get('emblem')->getData();
      $fileOrganization = $this->getParameter('organizations_emblems_directory');
      if ($emblem instanceof UploadedFile && is_string($fileOrganization)) {
        $organization->setEmblem($this->dataService->upload($emblem, $fileOrganization));
      }
      $this->dataService->flush();

      return $this->redirectToRoute('organization_index', ['_fragment' => $organization->getName()], Response::HTTP_SEE_OTHER);
    }

    return $this->render('organization/form.html.twig', [
      'form' => $form,
    ]);
  }
}
