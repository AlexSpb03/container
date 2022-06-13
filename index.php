<?php

use App\Container;

require __DIR__ . '/vendor/autoload.php';

class Test1
{
    private int $x;

    public function __construct(int $x)
    {
        $this->x = $x;
    }

    public function getX()
    {
        return $this->x;
    }
}

class Test2
{
    private int $y;

    public function __construct(int $y)
    {
        $this->y = $y;
    }

    public function getY()
    {
        return $this->y;
    }

}

class Test
{
    public function get(Test1 $test1, Test2 $test2, int $x, int $str)
    {
        echo "x = " .$x. PHP_EOL;
        echo "str = " .$str. PHP_EOL;
        echo $test1->getX() . PHP_EOL;
        echo $test2->getY() . PHP_EOL;
    }
}

$test1 = new Test1(5);
$test2 = new Test2(10);

Container::getInstance($test1);
Container::getInstance($test2);

Container::runMethod(Test::class, 'get', ['x' => 13]);