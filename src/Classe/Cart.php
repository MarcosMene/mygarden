<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

  public function __construct(private RequestStack $requestStack) {}
  
  /**
   * add product into shopping cart
   *
   * @param [type] $product
   * @return void
   */
  public function add($product)
  {

    //call symfony session CART
    $cart = $this->requestStack->getSession()->get('cart');

    //if product exist on cart
    if (isset($cart[$product->getId()])) {

      //add quantity +1
      $cart[$product->getId()] = [
        'object' => $product,
        'quantity' => $cart[$product->getId()]['quantity'] + 1
      ];
    } else {
      $cart[$product->getId()] = [
        'object' => $product,
        'quantity' => 1
      ];
    }

    //create cart session
    $this->requestStack->getSession()->set('cart', $cart);
  }


  /**
   * decrease product from cart
   *
   * @param [type] $id
   * @return void
   */
  public function decrease($id)
  {
    $cart = $this->getCart();

    //if product is in the cart and more than one, we can decrease the quantity
    if($cart[$id]['quantity'] > 1){
      $cart[$id]['quantity'] =  $cart[$id]['quantity'] - 1;
    }else{
      //if product is less than one, delete product from the cart
      unset($cart[$id]);
    }

    //update the session with the data
    $this->requestStack->getSession()->set('cart', $cart);
  }

    /**
   * decrease product from product page
   *
   * @param [type] $id
   * @return void
   */
  public function decreaseProduct($id)
  {
    $cart = $this->getCart();

    //if product is in the cart and more than one, we can decrease the quantity
    if($cart[$id]['quantity'] > 0){
      $cart[$id]['quantity'] =  $cart[$id]['quantity'] - 1;
    }else{
      //if product is less than one, delete product from the cart
      unset($cart[$id]);
    }

    //update the session with the data
    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function delete($id)
  {
    $cart = $this->getCart();
    unset($cart[$id]);

    //update session
    $this->requestStack->getSession()->set('cart', $cart);
  }


  /**
   * verify total products on the shopping cart
   *
   * @return void
   */
  public function fullQuantity()
  {
    $cart = $this->getCart();
    $total = 0;

    if (!isset($cart)) {
      return $total;
    }

    foreach ($cart as $product) {
      $total = $total + $product['quantity'];
    }
    return $total;
  }


/**
 * get total product with tax.
 *
 * @return void
 */
  public function getTotalWt()
  {
    $cart = $this->getCart();
    $price = 0;

    if (!isset($cart)) {
      return $price;
    }

    foreach ($cart as $product) {
      $price = $price + ($product['object']->getPriceWt()* $product['quantity']);
    }

    return $price;
  }
  public function getTotal()
  {
    $cart = $this->getCart();
    $price = 0;

    if (!isset($cart)) {
      return $price;
    }

    foreach ($cart as $product) {
      $price = $price + ($product['object']->getPrice()* $product['quantity']);
    }

    return $price;
  }

  public function getCart()
  {
    return $this->requestStack->getSession()->get('cart');
  }
}
