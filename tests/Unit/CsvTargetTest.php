<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Importer\Csv\Settings\CsvSourceSettings;
use App\Importer\Csv\CsvSource;

class CsvTargetTest extends TestCase
{   
    public function testCsvTarget()
    {
      $objSettings = CsvSourceSettings::builder()->
        withColumnDelimiter(";")->
        withFileName("input.csv")->build();
      
      $objCsvSource = new CsvSource($objSettings);
      $objOriginalName = $objCsvSource->getFieldByName("Name");
      $objHashName = $objOriginalName->transformField(
          new \App\Importer\Transformations\HashTransformation()
      );

      $objPregnant = $objCsvSource->getFieldByName("Pregnant")->transformField(
          (new \App\Importer\Transformations\RecodeTransformation())->
              add("Yes",1)->
              add("No",0)
      );

      $objCsvTarget = new \App\Importer\Csv\CsvTarget();
      $objCsvTarget->addFieldAtPosition($objPregnant, 1);
      $objCsvTarget->addFieldAtPositionWithName($objOriginalName, 0, "original-name");
      $objCsvTarget->addFieldNextPositionWithName($objHashName, "hash");
      
      $strExpectedContent = "" .
            "Pregnant;original-name;hash\n" .
            "1;Johnson;3013b18f4387bbe12cdb6d3ba9aa45a36adce32485da62113f97163f16beda66\n" .
            "0;Smith;9f542590100424c92a6ae40860f7017ac5dfbcff3cb49b36eace29b068e0d8e1\n" .
            "0;Lewis;c625ca57418821d8e717df1b71bf589a042d8fc0f0a2c3776090e155d2d377d3\n" .
            ";;";
      $this->assertEquals($strExpectedContent,$objCsvTarget->getCsvContent());
    }
}
