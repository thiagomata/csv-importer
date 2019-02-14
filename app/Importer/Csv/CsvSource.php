<?php

namespace App\Importer\Csv;

use App\Importer\Interfaces\Source;
use App\Importer\Interfaces\Field;
use App\Importer\Exceptions\SourceException;
use App\Importer\Csv\Settings\CsvSourceSettings;

class CsvSource implements Source {

  private $objSettings;

  private $objFile;

  private $arrFields = [];

  private $currentLine;
  
  public function __construct(CsvSourceSettings $settings) {
    $this->objSettings = $settings;
    $this->objFile = $this->openFile($settings->getFileName());
    if( $this->getSettings()->getHasHeader() ) {
      $this->loadFieldsWithHeader();
    } else {
      $this->loadFieldsWithoutHeader();
    }
  }

  public function getSettings() {
    return $this->objSettings;
  }

  private function openFile(string $fileName) {
    if( ! file_exists( $fileName ) ) {
      throw new SourceException("Unable to find the file {$fileName}");
    }
    $objFile = fopen( $fileName , 'r' );
    if( ! $objFile ) {
      throw new SourceException("Error reading the file {$fileName}. No permission to read.");
    }
    return $objFile;
  }

  private function loadFieldsWithHeader() {
    $this->loadLine();
    foreach( $this->currentLine as $intKey => $strFileName ) {
      $objField = new CsvField($this, $intKey, $strFileName, null);
      $this->arrFields[] = $objField;
    }
  }

  private function loadFieldsWithoutHeader() {
    $arrFirstLine = $this->loadLine();
    for( $intFieldPosition = 0; $intFieldPosition < count($this->currentLine); $intFieldPosition++ ) {
      $objField = new CsvField($this, $intFieldPosition);
      $this->arrFields[] = $objField;
    }
  }

  public function getFieldByPosition(int $fieldPosition): Field {
    if( ! array_key_exists($fieldPosition, $this->arrFields ) ) {
      throw new SourceException("Field ${$fieldPosition} does not exists");
    }
    return $this->arrFields[$fieldPosition];
  }

  public function getFieldByName(string $name): Field {
    foreach($this->arrFields as $objField) {
      if( $objField->getName() == $name ) {
        return $objField;
      }
    }
    throw new SourceException("Field ${name} does not exists");
  }
  
  public function getFields() {
      return $this->arrFields;
  }
  
  public function loadLine() {
    if( $this->eof() ) {
        return null;
    }
    $this->currentLine = fgetcsv(
      $this->objFile,
      0,
      $this->getSettings()->getColumnDelimiter()
    );
    if( $this->currentLine === false ) {
        $this->currentLine = array_fill(0,count($this->arrFields),null);
    }
  }
  
  public function getCurrentLine(): array {
      return $this->currentLine;
  }
  
  public function eof(): bool {
      return feof( $this->objFile );
  }
}
