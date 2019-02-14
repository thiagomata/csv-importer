<?php

namespace App\Importer\Csv\Settings;

use App\Importer\Csv\Builder\CsvSourceSettingsBuilder;
use App\Importer\Exceptions\SourceException;

class CsvSourceSettings {

  private $strFileName;

  private $strColumnDelimiter;

  private $booHasHeader;

  public static function builder(): CsvSourceSettingsBuilder {
    return new CsvSourceSettingsBuilder();
  }

  public function __construct(CsvSourceSettingsBuilder $builder) {
    $this->validate($builder);
    $this->strFileName = $builder->getFileName();
    $this->strColumnDelimiter = $builder->getColumnDelimiter();
    $this->booHasHeader = $builder->getHasHeader();
  }

  public function validate(CsvSourceSettingsBuilder $builder) {
    if( $builder->getFileName() == null ) {
      throw new SourceException("Invalid settings to the Source CSV. Missing File name " . $builder);
    }
  }

  public function getColumnDelimiter(): string {
    return $this->strColumnDelimiter;
  }

  public function getHasHeader(): bool {
    return $this->booHasHeader;
  }

  public function getFileName(): string {
    return $this->strFileName;
  }

  public function __toString() {
    return json_encode((array)$this, JSON_PRETTY_PRINT);
  }
}
