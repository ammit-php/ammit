<?php
declare(strict_types=1);

namespace Tests\Units\Imedia\Ammit\Domain;

use mageekguy\atoum;
use Imedia\Ammit\Domain\DateValidation as SUT;

/**
 * @author Guillaume MOREL <g.morel@imediafrance.fr>
 */
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
            ['01-01-2016', false],
            ['01 01 2016', false],
            ['01_01_2016', false],
            ['01.01.2016', false],
            ['2016-01-00', false],
            ['2016-01-01', true],
            ['2017-01-31', true],
            ['2017-01-32', false],
            ['2017-02-28', true],
            ['2017-02-29', false],
            ['2017-04-31', false],
        ];
    }
}
