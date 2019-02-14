<?php

namespace App\Importer\Transformations;

class DateTransformation extends AbstractTransformation {

  private $strFormatFrom;

  private $strFormatTo;

  public function __construct(string $strFormatFrom, string $strFormatTo = "Y-m-d") {
    $this->strFormatFrom = $strFormatFrom;
    $this->strFormatTo = $strFormatTo;
  }

  protected function apply(string $value): string {
    return date_create_from_format(
        $this->strFormatFrom, $value
    )->format( $this->strFormatTo );
  }
}
