<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Item;
use App\Entity\Items\Equipment;
use App\Entity\Items\RangedWeapon;
use App\Entity\Items\Vehicle;
use App\Entity\Items\Weapon;
use App\Form\EquipmentType;
use App\Form\ItemType;
use App\Form\RangedWeaponType;
use App\Form\VehicleType;
use App\Form\WeaponType;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
      'item' => [Item::class, ItemType::class],
      'equipment' => [Equipment::class, EquipmentType::class],
      'vehicle' => [Vehicle::class, VehicleType::class],
      'weapon' => [Weapon::class, WeaponType::class],
      'ranged_weapon' => [RangedWeapon::class, RangedWeaponType::class],
    ];
  }

  public function getItemList(string $type = null, int $id = null) : array
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
        $items = $repo->findBy(['owner' => null]);
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
