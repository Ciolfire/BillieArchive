<?php

namespace App\Entity\Types;

final class TypeNote {

  
  public const DEFAULT = 0;
  public const PAPER = 1;
  public const TERMINAL = 2;
  public const HANDWRITTING = 3;
  
  public const typeChoices = [
    'default' => TypeNote::DEFAULT,
    'paper' => TypeNote::PAPER,
    'terminal' => TypeNote::TERMINAL,
    'handwritting' => TypeNote::HANDWRITTING,
  ];

  public const typeNames = [
    TypeNote::DEFAULT => 'default',
    TypeNote::PAPER => 'paper',
    TypeNote::TERMINAL => 'terminal',
    TypeNote::HANDWRITTING => 'handwritting',
  ];
}