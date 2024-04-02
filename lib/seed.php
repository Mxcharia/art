<?php

class Seed extends Mysql
{
  public function seedUsers()
  {
    $userData = array(
      array('username' => array('val' => 'john_doe', 'type' => 's'), 'email' => array('val' => 'john@example.com', 'type' => 's'), 'user_type' => array('val' => 1, 'type' => 'i'), 'hash_password' => array('val' => password_hash('password123', PASSWORD_DEFAULT), 'type' => 's')),
      array('username' => array('val' => 'emma_smith', 'type' => 's'), 'email' => array('val' => 'emma@example.com', 'type' => 's'), 'user_type' => array('val' => 2, 'type' => 'i'), 'hash_password' => array('val' => password_hash('password123', PASSWORD_DEFAULT), 'type' => 's')),
      array('username' => array('val' => 'mike_jones', 'type' => 's'), 'email' => array('val' => 'mike@example.com', 'type' => 's'), 'user_type' => array('val' => 3, 'type' => 'i'), 'hash_password' => array('val' => password_hash('password123', PASSWORD_DEFAULT), 'type' => 's')),
      array('username' => array('val' => 'sara_miller', 'type' => 's'), 'email' => array('val' => 'sara@example.com', 'type' => 's'), 'user_type' => array('val' => 1, 'type' => 'i'), 'hash_password' => array('val' => password_hash('password123', PASSWORD_DEFAULT), 'type' => 's')),
      array('username' => array('val' => 'adam_wilson', 'type' => 's'), 'email' => array('val' => 'adam@example.com', 'type' => 's'), 'user_type' => array('val' => 2, 'type' => 'i'), 'hash_password' => array('val' => password_hash('password123', PASSWORD_DEFAULT), 'type' => 's')),
    );

    foreach ($userData as $data) {
      $result = $this->insertInto('users', $data);
      if (!$result) {
        echo "Error seeding users.\n";
        return false;
      }
    }

    return true;
  }
  public function seedGallery()
  {
    $galleryData = array(
      array('user_id' => array('val' => 1, 'type' => 'i'), 'name' => array('val' => 'City Art Gallery', 'type' => 's'), 'location' => array('val' => 'New York', 'type' => 's'), 'description' => array('val' => 'A gallery showcasing urban art.', 'type' => 's')),
      array('user_id' => array('val' => 2, 'type' => 'i'), 'name' => array('val' => 'Nature\'s Canvas', 'type' => 's'), 'location' => array('val' => 'Los Angeles', 'type' => 's'), 'description' => array('val' => 'A gallery featuring nature-inspired artworks.', 'type' => 's')),
      array('user_id' => array('val' => 3, 'type' => 'i'), 'name' => array('val' => 'Modern Art Loft', 'type' => 's'), 'location' => array('val' => 'Chicago', 'type' => 's'), 'description' => array('val' => 'A contemporary art gallery with loft-style exhibitions.', 'type' => 's')),
      array('user_id' => array('val' => 4, 'type' => 'i'), 'name' => array('val' => 'Vintage Visions', 'type' => 's'), 'location' => array('val' => 'San Francisco', 'type' => 's'), 'description' => array('val' => 'A gallery specializing in vintage and retro art.', 'type' => 's')),
      array('user_id' => array('val' => 5, 'type' => 'i'), 'name' => array('val' => 'Dreamy Dimensions', 'type' => 's'), 'location' => array('val' => 'Miami', 'type' => 's'), 'description' => array('val' => 'A gallery known for surreal and dream-like artworks.', 'type' => 's')),
    );

    foreach ($galleryData as $data) {
      $result = $this->insertInto('gallery', $data);
      if (!$result) {
        echo "Error seeding gallery.\n";
        return false;
      }
    }

    return true;
  }

  public function seedArt()
  {
    $artData = array(
      array('gallery_id' => array('val' => 1, 'type' => 'i'), 'name' => array('val' => 'Cityscape at Dusk', 'type' => 's'), 'art_url' => array('val' => 'https://plus.unsplash.com/premium_photo-1672287578303-a9832c84a2a3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTN8fGFydHxlbnwwfHwwfHx8MA%3D%3D', 'type' => 's'), 'artist' => array('val' => 'John Smith', 'type' => 's'), 'description' => array('val' => 'A beautiful painting capturing the city skyline at dusk.', 'type' => 's'), 'price' => array('val' => 500, 'type' => 'i')),
      array('gallery_id' => array('val' => 2, 'type' => 'i'), 'name' => array('val' => 'Sunset in the Forest', 'type' => 's'), 'art_url' => array('val' => 'https://images.unsplash.com/photo-1482160549825-59d1b23cb208?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTl8fGFydHxlbnwwfHwwfHx8MA%3D%3D', 'type' => 's'), 'artist' => array('val' => 'Emily Johnson', 'type' => 's'), 'description' => array('val' => 'A serene painting depicting a sunset scene in the forest.', 'type' => 's'), 'price' => array('val' => 750, 'type' => 'i')),
      array('gallery_id' => array('val' => 3, 'type' => 'i'), 'name' => array('val' => 'Abstract Reflections', 'type' => 's'), 'art_url' => array('val' => 'https://images.unsplash.com/photo-1598810132152-e70c939a1dfa?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTk4fHxhcnR8ZW58MHx8MHx8fDA%3D', 'type' => 's'), 'artist' => array('val' => 'Michael Brown', 'type' => 's'), 'description' => array('val' => 'An abstract artwork exploring reflections and light.', 'type' => 's'), 'price' => array('val' => 900, 'type' => 'i')),
      array('gallery_id' => array('val' => 4, 'type' => 'i'), 'name' => array('val' => 'Vintage Movie Poster', 'type' => 's'), 'art_url' => array('val' => 'https://images.unsplash.com/photo-1577142238654-92a366a70cd6?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGFic3RyYWN0JTIwc2N1bHB0dXJlfGVufDB8fDB8fHww', 'type' => 's'), 'artist' => array('val' => 'Sarah Clark', 'type' => 's'), 'description' => array('val' => 'A nostalgic poster for a classic film.', 'type' => 's'), 'price' => array('val' => 300, 'type' => 'i')),
      array('gallery_id' => array('val' => 5, 'type' => 'i'), 'name' => array('val' => 'Dreamy Seascape', 'type' => 's'), 'art_url' => array('val' => 'https://images.unsplash.com/photo-1615184697985-c9bde1b07da7?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YWJzdHJhY3QlMjBhcnR8ZW58MHx8MHx8fDA%3D', 'type' => 's'), 'artist' => array('val' => 'Adam Wilson', 'type' => 's'), 'description' => array('val' => 'A surreal seascape painting with vibrant colors.', 'type' => 's'), 'price' => array('val' => 600, 'type' => 'i')),
    );

    foreach ($artData as $data) {
      $result = $this->insertInto('art', $data);
      if (!$result) {
        echo "Error seeding art.\n";
        return false;
      }
    }

    return true;
  }

  public function seedExhibit()
  {
    $exhibitData = array(
      array('gallery_id' => array('val' => 1, 'type' => 'i'), 'event_image_url' => array('val' => 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YXJ0JTIwZ2FsbGVyeXxlbnwwfHwwfHx8MA%3D%3D', 'type' => 's'), 'name' => array('val' => 'Cityscapes Collection', 'type' => 's'), 'description' => array('val' => 'A collection of cityscape artworks.', 'type' => 's'), 'price' => array('val' => 1000, 'type' => 'i')),
      array('gallery_id' => array('val' => 2, 'type' => 'i'), 'event_image_url' => array('val' => 'https://plus.unsplash.com/premium_photo-1682088715035-11e11e28f50f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YXJ0JTIwZ2FsbGVyeXxlbnwwfHwwfHx8MA%3D%3D', 'type' => 's'), 'name' => array('val' => 'Nature\'s Beauty', 'type' => 's'), 'description' => array('val' => 'An exhibit celebrating the beauty of nature.', 'type' => 's'), 'price' => array('val' => 1200, 'type' => 'i')),
      array('gallery_id' => array('val' => 3, 'type' => 'i'), 'event_image_url' => array('val' => 'https://images.unsplash.com/photo-1564399580075-5dfe19c205f3?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8YXJ0JTIwZ2FsbGVyeXxlbnwwfHwwfHx8MA%3D%3D', 'type' => 's'), 'name' => array('val' => 'Abstract Expressions', 'type' => 's'), 'description' => array('val' => 'An exhibit featuring abstract artworks.', 'type' => 's'), 'price' => array('val' => 800, 'type' => 'i')),
      array('gallery_id' => array('val' => 4, 'type' => 'i'), 'event_image_url' => array('val' => 'https://images.unsplash.com/photo-1565876427310-0695a4ff03b7?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8YXJ0JTIwZ2FsbGVyeXxlbnwwfHwwfHx8MA%3D%3D', 'type' => 's'), 'name' => array('val' => 'Vintage Vibes', 'type' => 's'), 'description' => array('val' => 'A collection of vintage and retro art pieces.', 'type' => 's'), 'price' => array('val' => 950, 'type' => 'i')),
      array('gallery_id' => array('val' => 5, 'type' => 'i'), 'event_image_url' => array('val' => 'https://images.unsplash.com/photo-1590342823852-2ab98729f250?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MjB8fGFydCUyMGdhbGxlcnl8ZW58MHx8MHx8fDA%3D', 'type' => 's'), 'name' => array('val' => 'Dreamscapes', 'type' => 's'), 'description' => array('val' => 'An exhibit showcasing dreamy and surreal artworks.', 'type' => 's'), 'price' => array('val' => 1100, 'type' => 'i')),
    );

    foreach ($exhibitData as $data) {
      $result = $this->insertInto('exhibit', $data);
      if (!$result) {
        echo "Error seeding exhibit.\n";
        return false;
      }
    }

    return true;
  }

  public function seedCart()
  {
    $cartData = array(
      array('user_id' => array('val' => 1, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 1, 'type' => 'i'), 'quantity' => array('val' => 2, 'type' => 'i')),
      array('user_id' => array('val' => 2, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 3, 'type' => 'i'), 'quantity' => array('val' => 1, 'type' => 'i')),
      array('user_id' => array('val' => 3, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 5, 'type' => 'i'), 'quantity' => array('val' => 3, 'type' => 'i')),
      array('user_id' => array('val' => 4, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 2, 'type' => 'i'), 'quantity' => array('val' => 1, 'type' => 'i')),
      array('user_id' => array('val' => 5, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 4, 'type' => 'i'), 'quantity' => array('val' => 2, 'type' => 'i')),
    );

    foreach ($cartData as $data) {
      $result = $this->insertInto('cart', $data);
      if (!$result) {
        echo "Error seeding cart.\n";
        return false;
      }
    }

    return true;
  }

  public function seedOrder()
  {
    $orderData = array(
      array('user_id' => array('val' => 1, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 1, 'type' => 'i'), 'price' => array('val' => 1200, 'type' => 'i'), 'paid' => array('val' => true, 'type' => 'i')), // Convert true to array('val' => true, 'type' => 'i')
      array('user_id' => array('val' => 2, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 2, 'type' => 'i'), 'price' => array('val' => 900, 'type' => 'i'), 'paid' => array('val' => false, 'type' => 'i')), // Convert false to array('val' => false, 'type' => 'i')
      array('user_id' => array('val' => 3, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 3, 'type' => 'i'), 'price' => array('val' => 1800, 'type' => 'i'), 'paid' => array('val' => true, 'type' => 'i')), // Convert true to array('val' => true, 'type' => 'i')
      array('user_id' => array('val' => 4, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 4, 'type' => 'i'), 'price' => array('val' => 750, 'type' => 'i'), 'paid' => array('val' => true, 'type' => 'i')), // Convert true to array('val' => true, 'type' => 'i')
      array('user_id' => array('val' => 5, 'type' => 'i'), 'exhibit_id' => null, 'art_id' => array('val' => 5, 'type' => 'i'), 'price' => array('val' => 1600, 'type' => 'i'), 'paid' => array('val' => false, 'type' => 'i')), // Convert false to array('val' => false, 'type' => 'i')
    );

    foreach ($orderData as $data) {
      $result = $this->insertInto('`order`', $data);
      if (!$result) {
        echo "Error seeding orders.\n";
        return false;
      }
    }

    return true;
  }

  public function seedTransaction()
  {
    $transactionData = array(
      array('user_id' => array('val' => 1, 'type' => 'i'), 'order_id' => array('val' => 1, 'type' => 'i'), 'amount' => array('val' => 1200, 'type' => 'i')),
      array('user_id' => array('val' => 3, 'type' => 'i'), 'order_id' => array('val' => 3, 'type' => 'i'), 'amount' => array('val' => 1800, 'type' => 'i')),
      array('user_id' => array('val' => 4, 'type' => 'i'), 'order_id' => array('val' => 4, 'type' => 'i'), 'amount' => array('val' => 750, 'type' => 'i')),
    );

    foreach ($transactionData as $data) {
      $result = $this->insertInto('transaction', $data);
      if (!$result) {
        echo "Error seeding transactions.\n";
        return false;
      }
    }

    return true;
  }

  public function seedGalleryArt()
  {
    $galleryArtData = array(
      array('gallery_id' => array('val' => 1, 'type' => 'i'), 'art_id' => array('val' => 1, 'type' => 'i')),
      array('gallery_id' => array('val' => 2, 'type' => 'i'), 'art_id' => array('val' => 2, 'type' => 'i')),
      array('gallery_id' => array('val' => 3, 'type' => 'i'), 'art_id' => array('val' => 3, 'type' => 'i')),
      array('gallery_id' => array('val' => 4, 'type' => 'i'), 'art_id' => array('val' => 4, 'type' => 'i')),
      array('gallery_id' => array('val' => 5, 'type' => 'i'), 'art_id' => array('val' => 5, 'type' => 'i')),
    );

    foreach ($galleryArtData as $data) {
      $result = $this->insertInto('gallery_art', $data);
      if (!$result) {
        echo "Error seeding gallery_art.\n";
        return false;
      }
    }

    return true;
  }

  public function seedGalleryExhibit()
  {
    $galleryExhibitData = array(
      array('gallery_id' => array('val' => 1, 'type' => 'i'), 'exhibit_id' => array('val' => 1, 'type' => 'i')),
      array('gallery_id' => array('val' => 2, 'type' => 'i'), 'exhibit_id' => array('val' => 2, 'type' => 'i')),
      array('gallery_id' => array('val' => 3, 'type' => 'i'), 'exhibit_id' => array('val' => 3, 'type' => 'i')),
      array('gallery_id' => array('val' => 4, 'type' => 'i'), 'exhibit_id' => array('val' => 4, 'type' => 'i')),
      array('gallery_id' => array('val' => 5, 'type' => 'i'), 'exhibit_id' => array('val' => 5, 'type' => 'i')),
    );

    foreach ($galleryExhibitData as $data) {
      $result = $this->insertInto('gallery_exhibit', $data);
      if (!$result) {
        echo "Error seeding gallery_exhibit.\n";
        return false;
      }
    }

    return true;
  }

  public function seedUsersCart()
  {
    $usersCartData = array(
      array('users_id' => array('val' => 1, 'type' => 'i'), 'cart_user_id' => array('val' => 1, 'type' => 'i')),
      array('users_id' => array('val' => 2, 'type' => 'i'), 'cart_user_id' => array('val' => 2, 'type' => 'i')),
      array('users_id' => array('val' => 3, 'type' => 'i'), 'cart_user_id' => array('val' => 3, 'type' => 'i')),
      array('users_id' => array('val' => 4, 'type' => 'i'), 'cart_user_id' => array('val' => 4, 'type' => 'i')),
      array('users_id' => array('val' => 5, 'type' => 'i'), 'cart_user_id' => array('val' => 5, 'type' => 'i')),
    );

    foreach ($usersCartData as $data) {
      $result = $this->insertInto('users_cart', $data);
      if (!$result) {
        echo "Error seeding users_cart.\n";
        return false;
      }
    }

    return true;
  }


  public function seedOrderTransaction()
  {
    $orderTransactionData = array(
      array('order_id' => array('val' => 1, 'type' => 'i'), 'transaction_order_id' => array('val' => 1, 'type' => 'i')),
    );

    foreach ($orderTransactionData as $data) {
      $result = $this->insertInto('order_transaction', $data);
      if (!$result) {
        echo "Error seeding order_transaction.\n";
        return false;
      }
    }

    return true;
  }

  public function seedAllTables()
  {
    $result = $this->seedUsers();
    if (!$result) {
      return false;
    }

    $result = $this->seedGallery();
    if (!$result) {
      return false;
    }

    $result = $this->seedArt();
    if (!$result) {
      return false;
    }

    $result = $this->seedExhibit();
    if (!$result) {
      return false;
    }
    $result = $this->seedCart();
    if (!$result) {
      return false;
    }
    $result = $this->seedOrder();
    if (!$result) {
      return false;
    }
    $result = $this->seedTransaction();
    if (!$result) {
      return false;
    }
    $result = $this->seedUsersCart();
    if (!$result) {
      return false;
    }
    $result = $this->seedOrderTransaction();
    if (!$result) {
      return false;
    }
    echo "Database seeding is complete.\n";
  }
}
