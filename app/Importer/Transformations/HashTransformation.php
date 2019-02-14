<?php

namespace App\Importer\Transformations;

class HashTransformation extends AbstractTransformation {

  private $strHashAlgo = "sha256";

  public function setHashAlgo( string $hashAlgo ): HashTransformation{
    $this->strHashAlgo = $hashAlgo;
    return $this;
  }

  protected function apply(string $value): string {
    return hash($this->strHashAlgo, $value);
  }
}
