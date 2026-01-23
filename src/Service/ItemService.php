<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Item;
use App\Entity\Item\Armor;
use App\Entity\Item\Equipment;
use App\Entity\Item\RangedWeapon;
use App\Entity\Item\ThrownWeapon;
use App\Entity\Item\Vehicle;
use App\Entity\Item\Weapon;
use App\Form\Item\ArmorForm;
use App\Form\Item\ItemForm;
use App\Form\Item\EquipmentForm;
use App\Form\Item\RangedWeaponForm;
use App\Form\Item\ThrownWeaponForm;
use App\Form\Item\VehicleForm;
use App\Form\Item\WeaponForm;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\ItemRepository;

class ItemService
{
  private DataService $dataService;
  private ContainerBagInterface $params;

  private array $types;

  public function __construct(DataService $dataService, ContainerBagInterface $params)
  {
    $this->params = $params;
    $this->dataService = $dataService;
    $this->types = [
      'item' => [Item::class, ItemForm::class],
      'equipment' => [Equipment::class, EquipmentForm::class],
      'vehicle' => [Vehicle::class, VehicleForm::class],
      'weapon' => [Weapon::class, WeaponForm::class],
      'thrown_weapon' => [ThrownWeapon::class, ThrownWeaponForm::class],
      'ranged_weapon' => [RangedWeapon::class, RangedWeaponForm::class],
      'armor' => [Armor::class, ArmorForm::class],
    ];
  }

  public function getItemList(?string $type = null, ?int $id = null) : array
  {
    /** @var ItemRepository $repo */
    $repo = $this->dataService->getRepository(Item::class);

    switch ($type) {
      case 'book':
        /** @var Book */
        $subject = $this->dataService->findOneBy(Book::class, ['id' => $id]);
        $items = $subject->getItems();
        break;
      default:
        $items = $repo->findBy(['owner' => null, 'homebrewFor' => null], ['name' => 'ASC']);
        break;
    }

    return $items;
  }

  public function getTypes()
  {
    return $this->types;
  }

  public function getType(Item $item)
  {
    return $this->types[$item->getTypeName()];
  }

  public function save(Item $item, ?UploadedFile $img)
  {
    $path = $this->params->get('item_img_directory');
    if ($img instanceof UploadedFile && is_string($path)) {
      $item->setImg($this->dataService->upload($img, $path));
    }
    $this->dataService->save($item);
  }
}
