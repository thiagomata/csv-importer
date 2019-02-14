<?php

namespace App\Importer\Transformations;

class LowerCaseTransformation extends AbstractTransformation {

  protected function apply(string $value): string {
    return strtolower($value);
  }
}
