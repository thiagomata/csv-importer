<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Importer\Transformations\CamelCaseTransformation;
use \App\Importer\Transformations\UpperCaseTransformation;
use \App\Importer\Transformations\LowerCaseTransformation;
use \App\Importer\Transformations\DateTransformation;
use \App\Importer\Transformations\HashTransformation;
use \App\Importer\Transformations\MultiplyTransformation;
use \App\Importer\Transformations\RecodeTransformation;

class TransformationsTest extends TestCase
{

  public function testCamelCaseTransformation() {
    $objTransformation = new CamelCaseTransformation();
    $strResult = $objTransformation->transform("something new");
    $this->assertEquals("SomethingNew", $strResult);
  }

  public function testUpperCaseTransformation() {
    $objTransformation = new UpperCaseTransformation();
    $strResult = $objTransformation->transform("something new");
    $this->assertEquals("SOMETHING NEW", $strResult);
  }

  public function testLowerCaseTransformation() {
    $objTransformation = new LowerCaseTransformation();
    $strResult = $objTransformation->transform("SoMeThInG NEW");
    $this->assertEquals("something new", $strResult);
  }

  public function testDateTransformation() {
    $objTransformation = new DateTransformation("Y-m-d","d-m-Y");
    $strResult = $objTransformation->transform("2018-03-02");
    $this->assertEquals("02-03-2018", $strResult);
  }

  public function testHashTransformation() {
    $objTransformation = (new HashTransformation())->setHashAlgo("md5");
    $strResult = $objTransformation->transform("my secret");
    $this->assertEquals(md5("my secret"), $strResult);
  }

  public function testMultiplyTransformation() {
    $objTransformation = new MultiplyTransformation(2);
    $this->assertEquals("8", $objTransformation->transform(4));
    $this->assertEquals("16", $objTransformation->transform("a8a"));
    $this->assertEquals("5", $objTransformation->transform("a2b.5x"));
    $this->assertEquals("0", $objTransformation->transform("bob"));
  }

  public function testRecodeTransformation() {
    $objTransformation = new RecodeTransformation();
    $objTransformation->
      add("Bob","Joe")->
      add("Mary","Sarah");
    $this->assertEquals("Joe", $objTransformation->transform("Bob"));
    $this->assertEquals("Joe", $objTransformation->transform("Joe"));
    $this->assertEquals("Sarah", $objTransformation->transform("Mary"));
    $this->assertEquals("David", $objTransformation->transform("David"));
    $objTransformation->setNotFoundByDefault(true);
    $this->assertNull($objTransformation->transform("David"));
  }

  public function testTransfomationsCascade() {
    $objTransfCascade =
      (new CamelCaseTransformation())->
      then(new RecodeTransformation())->
      add("Thanos","Perfectly balanced")->
      add("Spider-Man","I don't feel so good")->
      then( new CamelCaseTransformation() );
    $this->assertEquals("PerfectlyBalanced",$objTransfCascade->transform("thanos"));
  }
}
