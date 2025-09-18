<?php

declare(strict_types=1);

namespace DaveLiddament\TestSplitter\Tests;

use DaveLiddament\TestSplitter\TestNameExtractor;
use PHPUnit\Framework\TestCase;

class TestNameExtractorTest extends TestCase
{
    private const ONE_TEST = <<<EOL
PHPUnit 11.5.27 by Sebastian Bergmann and contributors.

Available test:
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testIdentifier
EOL;

    private const NO_TESTS = <<<EOL
PHPUnit 11.5.27 by Sebastian Bergmann and contributors.

Available tests:
EOL;

    private const DUPLICATE_TEST_CLASS = <<<EOL
PHPUnit 11.5.27 by Sebastian Bergmann and contributors.

Available tests:
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testIdentifier
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testConversion
EOL;

    private const MANY_TESTS = <<<EOL
PHPUnit 9.0.0 by Sebastian Bergmann and contributors.

Available test(s):

 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\PhpmdJsonResultsParser\PhpmdJsonResultsParserTest::testGetIdentifier
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\PhpstanJsonResultsParser\PhpstanJsonResultsParserTest::testConversionFromString
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\PsalmJsonResultsParser\PsalmJsonResultsParserTest::testInvalidFileFormat#3
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testConversion
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testTypeGuesser
 - DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testInvalidJsonInput
EOL;

    /** @return iterable<array{string,array<int,string>}> */
    public static function inputToDeDupedTestClassNames(): iterable
    {
        yield [
            self::ONE_TEST,
            [
                'SarbJsonResultsParserTest',
            ],
        ];

        yield [
            self::NO_TESTS,
            [],
        ];

        yield [
            self::DUPLICATE_TEST_CLASS,
            [
                'SarbJsonResultsParserTest',
            ],
        ];

        yield [
            self::MANY_TESTS,
            [
                'PhpmdJsonResultsParserTest',
                'PhpstanJsonResultsParserTest',
                'PsalmJsonResultsParserTest',
                'SarbJsonResultsParserTest',
            ],
        ];
    }

    /**
     * @dataProvider inputToDeDupedTestClassNames
     *
     * @param array<int,string> $expectedTestNames
     */
    public function testGetDeDupedTestClassNames(string $input, array $expectedTestNames): void
    {
        $testNameExtractor = new TestNameExtractor();
        $actual = $testNameExtractor->getDeDupedTestClassNames($input);
        $this->assertSame($expectedTestNames, $actual);
    }

    /** @return iterable<array{string,array<int,string>}> */
    public static function inputToTestFunctionNames(): iterable
    {
        yield [
            self::ONE_TEST,
            [
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testIdentifier',
            ],
        ];

        yield [
            self::NO_TESTS,
            [],
        ];

        yield [
            self::DUPLICATE_TEST_CLASS,
            [
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testIdentifier',
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testConversion',
            ],
        ];

        yield [
            self::MANY_TESTS,
            [
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\PhpmdJsonResultsParser\PhpmdJsonResultsParserTest::testGetIdentifier',
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\PhpstanJsonResultsParser\PhpstanJsonResultsParserTest::testConversionFromString',
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\PsalmJsonResultsParser\PsalmJsonResultsParserTest::testInvalidFileFormat#3',
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testConversion',
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testTypeGuesser',
                'DaveLiddament\StaticAnalysisResultsBaseliner\Tests\Unit\Plugins\ResultsParsers\SarbJsonResultsParser\SarbJsonResultsParserTest::testInvalidJsonInput',
            ],
        ];
    }

    /**
     * @dataProvider inputToTestFunctionNames
     *
     * @param array<int,string> $expectedTestFunctionNames
     */
    public function testGetTestFunctionNames(string $input, array $expectedTestFunctionNames): void
    {
        $testNameExtractor = new TestNameExtractor();
        $actual = $testNameExtractor->getTestFunctionNames($input);
        $this->assertSame($expectedTestFunctionNames, $actual);
    }
}
