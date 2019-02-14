<?php

namespace App\Importer\Interfaces;

interface Source {
  public function getFieldByPosition(int $fieldPosition): Field;
  public function getFieldByName(string $fieldName): Field;
}
