<?php

namespace App\Importer\Interfaces;

interface Transformation {
  public function setPrevious(?Transformation $next);
  public function getPrevious(): ?Transformation;
  public function transform(?string $value): ?string;
  public function then(Transformation $transformation): Transformation;
}
