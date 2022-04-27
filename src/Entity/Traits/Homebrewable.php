<?php

namespace App\Entity\Traits;

trait Homebrewable {
  /**
   * @ORM\ManyToOne(targetEntity=Chronicle::class)
   */
  private $homebrewFor;
}