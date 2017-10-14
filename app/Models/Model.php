<?php
namespace App\Models;

use Exception;

abstract class Model
{
    public function __set($name, $value)
    {
        throw new Exception(sprintf('Classe "%s" não possui propriedade "%s"', get_class($this), $name));
    }
}
