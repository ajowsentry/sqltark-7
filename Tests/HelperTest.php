<?php

declare(strict_types=1);

require_once __DIR__. '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use SqlTark\Utilities\Helper;

final class HelperTest extends TestCase
{
    public function testReplaceAll_itShouldKeepItAsIt()
    {
        $callback = function($x) { return $x . ''; };

        $input = '';
        $output = Helper::replaceAll($input, 'any', $callback);
        $this->assertEquals($input, $output);
        
        $input = null;
        $output = Helper::replaceAll($input, 'any', $callback);
        $this->assertEquals($input, $output);
        
        $input = ' ';
        $output = Helper::replaceAll($input, 'any', $callback);
        $this->assertEquals($input, $output);
        
        $input = '  ';
        $output = Helper::replaceAll($input, 'any', $callback);
        $this->assertEquals($input, $output);
        
        $input = '   ';
        $output = Helper::replaceAll($input, 'any', $callback);
        $this->assertEquals($input, $output);
    }

    public function testReplaceAll_replaceBeginning()
    {
        $callback = function() { return '@'; };
        
        $output = Helper::replaceAll('hello', '?', $callback);
        $this->assertEquals('hello', $output);
        
        $output = Helper::replaceAll('?hello', '?', $callback);
        $this->assertEquals('@hello', $output);
        
        $output = Helper::replaceAll('??hello', '?', $callback);
        $this->assertEquals('@@hello', $output);
        
        $output = Helper::replaceAll('?? hello', '?', $callback);
        $this->assertEquals('@@ hello', $output);
        
        $output = Helper::replaceAll('? ? hello', '?', $callback);
        $this->assertEquals('@ @ hello', $output);
        
        $output = Helper::replaceAll(' ? ? hello', '?', $callback);
        $this->assertEquals(' @ @ hello', $output);
    }

    public function testReplaceAll_replaceEnding()
    {
        $callback = function() { return '@'; };
        
        $output = Helper::replaceAll('hello', '?', $callback);
        $this->assertEquals('hello', $output);
        
        $output = Helper::replaceAll('hello?', '?', $callback);
        $this->assertEquals('hello@', $output);
        
        $output = Helper::replaceAll('hello??', '?', $callback);
        $this->assertEquals('hello@@', $output);
        
        $output = Helper::replaceAll('hello?? ', '?', $callback);
        $this->assertEquals('hello@@ ', $output);
        
        $output = Helper::replaceAll('hello? ? ', '?', $callback);
        $this->assertEquals('hello@ @ ', $output);
        
        $output = Helper::replaceAll('hello ? ? ', '?', $callback);
        $this->assertEquals('hello @ @ ', $output);
    }

    public function testReplaceAll_replaceWithPosition()
    {
        $callback = function($x) { return $x; };
        
        $output = Helper::replaceAll('hello', '?', $callback);
        $this->assertEquals('hello', $output);
        
        $output = Helper::replaceAll('hello?', '?', $callback);
        $this->assertEquals('hello0', $output);
        
        $output = Helper::replaceAll('hello??', '?', $callback);
        $this->assertEquals('hello01', $output);
        
        $output = Helper::replaceAll('hello?? ', '?', $callback);
        $this->assertEquals('hello01 ', $output);
        
        $output = Helper::replaceAll('hello? ? ', '?', $callback);
        $this->assertEquals('hello0 1 ', $output);
        
        $output = Helper::replaceAll('hello ? ? ', '?', $callback);
        $this->assertEquals('hello 0 1 ', $output);
    }

    public function testExtractAlias_shouldKeepSingle()
    {
        $output = Helper::extractAlias('table');
        $this->assertEquals(['table'], $output);

        $output = Helper::extractAlias(' table ');
        $this->assertEquals(['table'], $output);

        $output = Helper::extractAlias('     table     ');
        $this->assertEquals(['table'], $output);

        $output = Helper::extractAlias('');
        $this->assertEquals([], $output);
    }

    public function testExtractAlias_shouldReturnPair()
    {
        $output = Helper::extractAlias('table as t');
        $this->assertEquals(['table', 't'], $output);

        $output = Helper::extractAlias('table As t');
        $this->assertEquals(['table', 't'], $output);

        $output = Helper::extractAlias('table AS t');
        $this->assertEquals(['table', 't'], $output);

        $output = Helper::extractAlias('table aS t');
        $this->assertEquals(['table', 't'], $output);

        $output = Helper::extractAlias('table as t as tt');
        $this->assertEquals(['table', 't as tt'], $output);
    }
}