<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Importer\Csv\Settings\CsvSourceSettings;
use App\Importer\Csv\CsvSource;

class CsvSourceTest extends TestCase
{
    public function testCsvSource()
    {
      $objSettings = CsvSourceSettings::builder()->
        withColumnDelimiter(";")->
        withFileName("input.csv")->build();
      $objCsvSource = new CsvSource($objSettings);
      $this->assertEquals(
        1,
        $objCsvSource->getFieldByName("Name")->getPosition()
      );
      $this->assertEquals(
        "Patient ID",
        $objCsvSource->getFieldByPosition(0)->getName()
      );
    }
}
