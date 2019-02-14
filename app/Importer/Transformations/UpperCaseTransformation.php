<?php

namespace App\Importer\Transformations;

class UpperCaseTransformation extends AbstractTransformation {

  protected function apply(string $value): string {
    return strtoupper($value);
  }
}
