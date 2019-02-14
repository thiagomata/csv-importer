<?php

namespace App\Importer\Interfaces;

interface Target {
  public function addFieldAtPosition(Field $field, int $position): Target;
  public function addFieldAtPositionWithName(Field $field, int $position, string $fieldName): Target;
  public function addFieldNextPositionWithName(Field $field, string $fieldName): Target;
}
