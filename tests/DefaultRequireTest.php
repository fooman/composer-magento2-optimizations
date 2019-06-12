<?php

namespace Fooman\ComposerMagentoOptimizations\Tests;

use Composer\Semver\VersionParser;
use PHPUnit\Framework\TestCase;
use Fooman\ComposerMagentoOptimizations\Plugin;

class DefaultRequireTest extends TestCase
{

    /**
     * Tests default require provider.
     *
     * @param $provided
     * @param $expected
     * @dataProvider provideTestData
     */
    public function testDefaultRequire($provided, $expected)
    {
        $versionParser = new VersionParser();
        self::assertEquals($expected, Plugin::getDefaultRequire($versionParser->parseConstraints($provided)));
    }

    /**
     * Test data.
     */
    function provideTestData()
    {
        yield 'exact-below' => ['2.1.0', []];
        yield 'exact-above' => ['2.4.0', ['symfony/symfony' => '>4.1']];
        yield 'exact-min' => ['2.3.0', ['symfony/symfony' => '>4.1']];
        yield 'range-below' => ['~2.2.0', []];
        yield 'range-overlapping' => ['>2.2.0 <2.4.0', []];
        yield 'range-below-above' => ['~2.1.0|~2.4.0', []];
        yield 'range-above' => ['~2.4.0', ['symfony/symfony' => '>4.1']];
        yield 'range-min' => ['^2.3', ['symfony/symfony' => '>4.1']];
    }

}
