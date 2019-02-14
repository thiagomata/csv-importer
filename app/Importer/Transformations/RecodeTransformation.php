<?php

namespace App\Importer\Transformations;

class RecodeTransformation extends AbstractTransformation {

  private $arrDic = [];

  /**
   * If true, replace all the values
   * that are not on the dicionary
   * by the default value
   * @var bool
   */
  private $notFoundForDefault = false;

  public function add(string $strFrom, string $strTo) {
    $this->arrDic[$strFrom] = $strTo;
    return $this;
  }

  public function setNotFoundByDefault(bool $notFoundByDefault = true) {
    $this->notFoundForDefault = $notFoundByDefault;
  }

  protected function apply(string $value): ?string {
    if( array_key_exists($value,$this->arrDic) ) {
      return $this->arrDic[$value];
    } else {
      return $this->notFoundForDefault ? $this->getDefaultValue() : $value;
    }
  }
}
