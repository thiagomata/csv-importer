<?php
namespace App\Importer\Csv\Builder;

use App\Importer\Csv\Settings\CsvSourceSettings;

class CsvSourceSettingsBuilder {

  private $strFileName;

  private $strColumnDelimiter = ",";

  private $booHasHeader = true;

  public function build(): CsvSourceSettings {
    return new CsvSourceSettings($this);
  }

  public function withColumnDelimiter(string $columnDelimiter): CsvSourceSettingsBuilder {
    $this->strColumnDelimiter = $columnDelimiter;
    return $this;
  }

  public function getColumnDelimiter(): string {
    return $this->strColumnDelimiter;
  }

  public function withHasHeader(bool $hasHeader): CsvSourceSettingsBuilder {
    $this->booHasHeader = $hasHeader;
    return $this;
  }

  public function getHasHeader(): bool {
    return $this->booHasHeader;
  }

  public function withFileName(string $fileName): CsvSourceSettingsBuilder {
    $this->strFileName = $fileName;
    return $this;
  }

  public function getFileName(): ?string {
    return $this->strFileName;
  }

  public function __toString() {
    return json_encode((array)$this, JSON_PRETTY_PRINT);
  }
}
