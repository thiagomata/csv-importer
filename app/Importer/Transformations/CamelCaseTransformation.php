<?php

namespace App\Importer\Transformations;

class CamelCaseTransformation extends AbstractTransformation {

  protected function apply(string $value): string {
      $arrWords = explode( " ", $value );
      $arrUpWorkds = array_map(
        function( $word ) {
          return ucfirst(strtolower($word));
        },
        $arrWords
      );
      return implode("", $arrUpWorkds);
  }
}
