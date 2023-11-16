<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    public function dataGetFahrenheit(): array{
        return[
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['14.3', 57.74],
            ['-59.23', -74.614],
            ['780', 1436],
            ['0.1', 32.18],
            ['-0.2', 31.64],
            ['-49', -56.2],
            ['49', 120.2]
        ];
    }

    /**
     *@dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }
}
