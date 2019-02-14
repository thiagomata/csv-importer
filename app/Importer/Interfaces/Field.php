<?php

namespace App\Importer\Interfaces;

interface Field {
  public function getName(): ?string;
  public function getPosition(): int;
  public function transformField(Transformation $transformation): Field;
  public function getCurrentValue(): ?string;
  // public function __construct(
  //   Source $source,
  //   int $position,
  //   ?string $name,
  //   ?Transformation $transformation
  // );
}
