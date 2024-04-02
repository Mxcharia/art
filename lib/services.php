<?php
include 'sql.php';

class Services extends Mysql
{
  public $user_id;
  function __construct($user_id)
  {
    parent::__construct();

    $this->user_id = $user_id;
  }
  // $quantity argument can be null
  function add_art_to_cart($art_id, ?int $quantity)
  {
    $quantity = $quantity ?? 1;
    $query = "SELECT * FROM cart WHERE user_id = $this->user_id AND art_id = $art_id";
    $cart_item = $this->selectfreerun($query);
    $cart_item = mysqli_fetch_assoc($cart_item);
    if ($cart_item) {
      // If the item already exists, update the quantity

      $quantity = $cart_item['quantity'] + $quantity;
      $this->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $this->user_id AND art_id = $art_id");
    } else {
      // If the item doesn't exist, insert a new record
      $this->freerun("INSERT INTO cart (user_id, art_id, quantity) VALUES ($this->user_id, $art_id, 1)");
    }
  }
  function reduce_art_to_cart($cart_id, ?int $quantity)
  {
    $quantity = $quantity ?? 1;
    $query = "SELECT * FROM cart WHERE user_id = $this->user_id AND id = $cart_id";
    $cart_item_result = $this->selectfreerun($query);
    // Check if the query was successful
    if ($cart_item_result) {
      $cart_item = mysqli_fetch_assoc($cart_item_result);
      if ($cart_item) {
        // If the item already exists, update the quantity
        if ((int)$cart_item['quantity'] <= 1) {
          $id = $cart_item["id"];
          $this->freerun("DELETE FROM cart WHERE id=$id");
        } else {
          $quantity = $cart_item['quantity'] - $quantity;
          $this->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $this->user_id AND id = $cart_id");
        }
      } else {
        // If cart item not found, handle it accordingly
        error_log("Cart item not found for user_id=$this->user_id and cart id=$cart_id");
      }
    } else {
      // Handle the case when the query fails
      error_log("Query failed: $query");
    }
  }

  function delete_art_from_cart($cart_id)
  {
    // Check if the query was successful
    if ((int)$cart_id) {
      // If the item already exists, update the quantity
      $this->freerun("DELETE FROM cart WHERE id=$cart_id");
    } else {
      // Handle the case when the query fails
      error_log("Query failed");
    }
  }
  function add_event_to_cart($exhibit_id, ?int $quantity)
  {
    if ($quantity === null) {
      $quantity = 1;
    }
    $query = "SELECT * FROM cart WHERE user_id = $this->user_id AND exhibit_id = $exhibit_id";
    $cart_item = $this->selectfreerun($query);
    $cart_item = mysqli_fetch_assoc($cart_item);
    if ($cart_item) {
      // If the item already exists, update the quantity
      $quantity = $cart_item['quantity'] + $quantity;
      $this->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $this->user_id AND exhibit_id = $exhibit_id");
    } else {
      // If the item doesn't exist, insert a new record
      $this->freerun("INSERT INTO cart (user_id, exhibit_id, quantity) VALUES ($this->user_id, $exhibit_id, 1)");
    }
  }
}
