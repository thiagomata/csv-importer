<?php

namespace App\Importer\Transformations;

use App\Importer\Interfaces\Transformation;

abstract class AbstractTransformation implements Transformation {

  private $objPrevious;

  public function setPrevious(?Transformation $previous) {
    $this->objPrevious = $previous;
  }

  public function getPrevious(): ?Transformation {
    return $this->objPrevious;
  }

  public function hasPrevious(): bool {
    return $this->objPrevious !== null;
  }

  abstract protected function apply(string $value): ?string;

  protected function getDefaultValue(): ?string {
    return null;
  }

  public function transform(?string $value): ?string {
      if( $value === null ) {
        return $this->getDefaultValue();
      }
      if( $this->hasPrevious() ) {
        return $this->apply(
          $this->getPrevious()->transform(
            $value
          )
        );
      } else {
        return $this->apply(
          $value
        );
      }
  }

  public function then(Transformation $previousTransformation): Transformation {
    $previousTransformation->setPrevious( $this );
    return $previousTransformation;
  }
}
