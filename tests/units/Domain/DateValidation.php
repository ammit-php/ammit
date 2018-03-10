<?php
declare(strict_types=1);

namespace Tests\Units\AmmitPhp\Ammit\Domain;

use mageekguy\atoum;
use AmmitPhp\Ammit\Domain\DateValidation as SUT;

class DateValidation extends atoum
{
    /**
     * @dataProvider dateProvider
     */
    public function test_it_validate_date(string $date, bool $isGood)
    {
        // Given
        $sut = new SUT();

        // When
        $actual = $sut->isDateValid($date);

        // Then
        $this
            ->boolean($actual)
                ->isEqualTo($isGood);
    }

    protected function dateProvider(): array
    {
        return [
            ['azerty', false],
            ['01-01-2017', false],
            ['01 01 2017', false],
            ['01_01_2017', false],
            ['01.01.2017', false],
            ['2017-01-00', false],
            ['2017-01-01', true],
            ['2017-01-31', true],
            ['2017-01-32', false],
            ['2017-02-28', true],
            ['2017-02-29', false],
            ['2017-04-31', false],
        ];
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function test_it_validate_datetime(string $date, bool $isGood)
    {
        // Given
        $sut = new SUT();

        // When
        $actual = $sut->isDateTimeValid($date);

        // Then
        $this
            ->boolean($actual)
                ->isEqualTo($isGood);
    }

    protected function dateTimeProvider(): array
    {
        return [
            ['azerty', false],
            ['01-01-2017', false],
            ['01_01_2017T00:00:00+00:00', false],
            ['01.01.2017T00:00:00+00:00', false],
            ['2017-01-00T00:00:00+00:00', false],
            ['2017-01-01T00:00:00+00:00', true],
            ['2017-01-31T00:00:00+00:00', true],
            ['2017-01-32T00:00:00+00:00', false],
            ['2017-02-28T00:00:00+00:00', true],
            ['2017-02-29T00:00:00+00:00', false],
            ['2017-04-31T00:00:00+00:00', false],
            ['2017-02-28T23:59:59+00:00', true],
            ['2017-02-28T24:00:00+00:00', false],
        ];
    }
}
