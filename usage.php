<?php
require_once __DIR__ . '/src/Enum.func.php';
@mkdir(__DIR__ . '/cache');
EnumGenerator::setDefaultCachedClassesDir(__DIR__ . '/cache');

//Class definition is evaluated on the fly:
Enum('FruitsEnum', array('apple' , 'orange' , 'rasberry' , 'bannana'));

//Class definition is cached in the cache directory for later usage:
Enum('CachedFruitsEnum', array('apple' => 'pig' , 'orange' => 'dog' , 'rasberry' => 'cat' , 'bannana' => 'bird'), 'my\company\name\space', true);

echo 'FruitsEnum::APPLE() == FruitsEnum::APPLE(): ';
var_dump(FruitsEnum::APPLE() == FruitsEnum::APPLE()) . "\n";

echo 'FruitsEnum::APPLE() == FruitsEnum::ORANGE(): ';
var_dump(FruitsEnum::APPLE() == FruitsEnum::ORANGE()) . "\n";

echo 'FruitsEnum::APPLE() instanceof Enum: ';
var_dump(FruitsEnum::APPLE() instanceof Enum) . "\n";

echo 'FruitsEnum::APPLE() instanceof FruitsEnum: ';
var_dump(FruitsEnum::APPLE() instanceof FruitsEnum) . "\n";

echo "\n";
echo "Namespace support: \n";
var_dump(my\company\name\space\CachedFruitsEnum::APPLE());

echo "\n";
echo "File caching: \n";
foreach (array('FruitsEnum', 'my\company\name\space\CachedFruitsEnum') as $class)
{
  echo "class: $class\n";
  $r = new ReflectionClass($class);
  echo 'File: ' . $r->getFileName() . "\n";
}

echo "\n";
echo "->getName()\n";
foreach (FruitsEnum::iterator() as $enum)
{
  echo "  " . $enum->getName() . "\n";
}

echo "\n";
echo "->getValue()\n";
foreach (FruitsEnum::iterator() as $enum)
{
  echo "  " . $enum->getValue() . "\n";
}

echo "\n";
echo "->getValue() when values have been specified\n";
foreach (my\company\name\space\CachedFruitsEnum::iterator() as $enum)
{
  echo "  " . $enum->getValue() . "\n";
}

echo "\n";
echo "->getOrdinal()\n";
foreach (my\company\name\space\CachedFruitsEnum::iterator() as $enum)
{
  echo "  " . $enum->getOrdinal() . "\n";
}

echo "\n";
echo "->getBinary()\n";
foreach (my\company\name\space\CachedFruitsEnum::iterator() as $enum)
{
  echo "  " . $enum->getBinary() . "\n";
}

echo "\n";
echo "/*cast:*/ (string) \$enum\n";
foreach (FruitsEnum::iterator() as $enum)
{
  echo "  " . (string) $enum . "\n";
}
