<?php

namespace App\Importer\Csv;

use App\Importer\Interfaces\Field;
use App\Importer\Interfaces\Transformation as Transformation;

class CsvField implements Field {

  private $strName;

  private $intPosition;

  private $objSource;

  private $objTransformation;

  public function getName(): ?string {
    return $this->strName;
  }

  public function getPosition(): int {
    return $this->intPosition;
  }

  public function getSource(): CsvSource {
    return $this->objSource;
  }

  public function getTransformation(): ?Transformation {
    return $this->objTransformation;
  }

  public function getCurrentValue(): ?string {
      $originalValue = $this->getSource()->getCurrentLine()[$this->getPosition()];
      if( $this->objTransformation === null ){
          return $originalValue;
      } else {
          return $this->objTransformation->transform($originalValue);
      }
  }
  public function transformField(Transformation $transformation): Field {
    $objPreviousTransformation = $this->getTransformation();
    if( $objPreviousTransformation !== null ) {
      $objNextTransformation = $objPreviousTransformation->then( $transformation );
    } else {
      $objNextTransformation = $transformation;
    }
    $objNewField = new CsvField(
      $this->getSource(),
      $this->getPosition(),
      $this->getName(),
      $objNextTransformation
    );
    return $objNewField;
  }

  public function __construct(
    CsvSource $source,
    int $position,
    ?string $name = null,
    ?Transformation $objTransformation = null
  ) {
      $this->objSource = $source;
      $this->intPosition = $position;
      $this->strName = trim($name);
      $this->objTransformation = $objTransformation;
  }
}
