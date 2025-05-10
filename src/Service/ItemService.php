<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\Item;
use App\Entity\Items\Equipment;
use App\Entity\Items\RangedWeapon;
use App\Entity\Items\Vehicle;
use App\Entity\Items\Weapon;
use App\Form\EquipmentForm;
use App\Form\ItemForm;
use App\Form\RangedWeaponForm;
use App\Form\VehicleForm;
use App\Form\WeaponForm;
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
      'ranged_weapon' => [RangedWeapon::class, RangedWeaponForm::class],
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
