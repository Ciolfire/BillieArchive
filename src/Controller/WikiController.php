<?php

namespace App\Controller;

use App\Repository\AttributeRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale<%supported_locales%>?%default_locale%}/wiki")
 */
class WikiController extends AbstractController
{
  /**
   * @Route("/", name="wiki_index", methods={"GET"})
   */
  public function index(): Response
  {
    return $this->render('wiki/index.html.twig', [
      'type' => 'human',
    ]);
  }

  /**
   * @Route("/attribute", name="attribute_index", methods={"GET"})
   */
  public function attributes(AttributeRepository $attributeRepository): Response
  {
    return $this->render('wiki/list.html.twig', [
      'elements' => $attributeRepository->findAll(),
      'entity' => 'attribute',
      'category' => 'character',
      'type' => 'human',
    ]);
  }

  /**
   * @Route("/skill", name="skill_index", methods={"GET"})
   */
  public function skills(SkillRepository $skillRepository): Response
  {
    return $this->render('wiki/list.html.twig', [
      'elements' => $skillRepository->findAll(),
      'entity' => 'skill',
      'category' => 'character',
      'type' => 'human',
    ]);
  }
}