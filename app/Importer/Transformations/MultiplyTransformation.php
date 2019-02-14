<?php

namespace App\Importer\Transformations;

class MultiplyTransformation extends AbstractTransformation {

  private $fltFactor;

  private $strDecimalSeparator = ".";

  const NUMBERS = ["0","1","2","3","4","5","6","7","8","9"];

  const NEGATIVE_SIGNAL = "-";

  private function castAsNumber(string $strText): float {
    $strResult = "";
    $boolHasDecimal = false;
    for( $i = 0; $i < strlen( $strText ); ++$i )
    {
        $charLetter = $strText[$i];
        if( $i == 0 && $charLetter == self::NEGATIVE_SIGNAL )
        {
            $strResult .= $charLetter;
        }
        else if( in_array($charLetter, self::NUMBERS) )
        {
            $strResult .= $charLetter;
        }
        else if( ! $boolHasDecimal && $charLetter == $this->strDecimalSeparator )
        {
            $strResult .= ".";
        }
    }
    if( $strResult === "" ){
      return 0;
    }
    return $strResult += 0;
  }

  public function __construct(float $factor) {
      $this->fltFactor = $factor;
  }

  public function setDecimalSeparator(string $decimalSeparator) {
      $this->strDecimalSeparator = $decimalSeparator;
  }

  protected function apply(string $value): string {
    return ( $this->castAsNumber($value) * $this->fltFactor ) . "";
  }
}
