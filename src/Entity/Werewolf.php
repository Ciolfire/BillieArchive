<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\WerewolfRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: WerewolfRepository::class)]
class Werewolf extends Character
{
}