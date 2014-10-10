<?php

namespace Caledonia;

class Debug {
  
  static public function dump($data) {
    echo '<pre style="border: 1px solid #ccc; background: #eee; padding: 10px;">' . var_export($data, 1) . '</pre>' . "\n";
  }
}


