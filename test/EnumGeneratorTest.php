<?php

require_once __DIR__ . '/EnumTestCase.class.php';
require_once __DIR__ . '/../src/EnumGenerator.class.php';

/**
 *  @backupStaticAttributes enabled
 */
class EnumGeneratorTest extends EnumTestCase
{

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp()
  {
    parent::setUp();
    restore_error_handler();
    EnumGenerator::setDefaultCachedClassesDir($this->tmpDir);
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown()
  {
    `rm -rf $this->tmpDir`;
    parent::tearDown();
  }

  /**
   * @test
   * @testdox ->generate() should return a string
   */
  public function generateReturnString()
  {
    $r = EnumGenerator::getInstance()->generate('dummy', array('orange'));
    $this->assertTrue(is_string($r));
  }

  /**
   * @test
   * @testdox ->generate() should return a valid php content
   */
  public function generateValidPhpContent()
  {
    set_error_handler(function($errno, $errstr, $errfile, $errline)use(&$hasError){
      throw new PHPUnit_Framework_Error($errstr);
    });
    $r = EnumGenerator::getInstance()->generate('dummy', array('apple'));
    eval($r);
    $this->assertTrue(true);
  }

  /**
   * @test
   * @testdox ->generate() $instances parameter should be a non empty array
   * @expectedException InvalidArgumentException
   */
  public function generateInstancesParameterShouldNotBeEmpty()
  {
    EnumGenerator::getInstance()->generate('MyEnum', array());
  }

  /**
   * @test
   * @testdox ->generate() should return a valid class definition
   */
  public function generateValidClassDef()
  {
    $r = EnumGenerator::getInstance()->generate('MyFirstEnum', array('apple'));
    eval($r);
    $this->assertTrue(class_exists('MyFirstEnum'));
  }

  /**
   * @test
   * @testdox ->generate() should return a valid class definition when a simple namespace is provided
   */
  public function generateValidClassDefWithSimpleNamesapce()
  {
    $r = EnumGenerator::getInstance()->generate('MyFirstEnumWithNamespace', array('apple'), 'zanshine');
    eval($r);
    $this->assertTrue(class_exists('\zanshine\MyFirstEnumWithNamespace'));
  }

  /**
   * @test
   * @testdox ->generate() should return a valid class definition when a deep namespace is provided
   */
  public function generateValidClassDefWithDeepNamesapce()
  {
    $r = EnumGenerator::getInstance()->generate('MySecondEnumWithNamespace', array('apple'), 'com\zanshine');
    eval($r);
    $this->assertTrue(class_exists('\com\zanshine\MySecondEnumWithNamespace'));
  }

  /**
   * @test
   * @testdox ->compil() return a valid filename
   */
  public function compilReturnValidFilename()
  {
    $f = EnumGenerator::getInstance()->compil('MySecondEnum', array('apple'));
    $this->assertFileExists($f);
  }

  /**
   * @test
   * @testdox ->compil() compiled file is requirable
   */
  public function compiledFileIsRequirable()
  {
    $f = EnumGenerator::getInstance()->compil('MyThirdEnum', array('apple'));
    require $f;
    $this->assertTrue(true);
  }

  /**
   * @test
   * @testdox ->compil() compiled file is a valid class def
   */
  public function compiledFileIsValidClassDef()
  {
    $f = EnumGenerator::getInstance()->compil('OneMoreEnum', array('apple'));
    require $f;
    $this->assertTrue(class_exists('OneMoreEnum'));
  }

  /**
   * @test
   * @testdox ->evaluate() should evaluate the generated class definition
   */
  public function evalShouldEvaluateClassDef()
  {
    EnumGenerator::getInstance()->evaluate('OnTheFly', array('apple'));
    OnTheFly::APPLE();
    $this->assertTrue(true);
  }

  /**
   * @test
   * @testdox ->generate() $instances names should be sanitized
   */
  public function generateInstancesParameterShouldTrim()
  {
    EnumGenerator::getInstance()->evaluate('TrimEnum', array('an apple'));
    $this->assertEquals('an apple', TrimEnum::AN_APPLE()->getValue());
  }
}

