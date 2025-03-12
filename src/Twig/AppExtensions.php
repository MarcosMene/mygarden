<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtensions extends AbstractExtension implements GlobalsInterface
{

  private $cart;

  public function __construct(Cart $cart)
  {
    $this->cart = $cart;
  }

  //FUNCTION TO DISPLAY THE PRICE FORMAT
  public function getFilters()
  {
    return [
      new TwigFilter('price', [$this, 'formatPrice']),
    ];
  }

  public function formatPrice($number)
  {
    return '$' . number_format($number, 2, '.', ' ');
  }

  //VARIABLE GLOBAL GET ALL CART ON ALL PAGES
  public function getGlobals(): array
  {
    return [
      'fullCartQuantity' => $this->cart->fullQuantity(),
      'myCart' => $this->cart->getCart(),
      'totalCartNt' => $this->cart->getTotal(),
      'totalCartWt' => $this->cart->getTotalWt(),
    ];
  }
}
