<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }

    public function dataGetFahrenheit(): array
    {
        return [
            [0, 32],
            [-100, -148],
            [100, 212],
            [0.5, 32.9],
            [37, 98.6],
            [21, 69.8],
            [-40, -40],
            [25.6, 78.08],
            [250, 482],
            [15, 59]
        ];
    }
}
