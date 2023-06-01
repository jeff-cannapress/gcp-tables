<?php

namespace CannaPress\GcpTables\Tests;


use CannaPress\GcpTables\Filters\TableFilter;
use CannaPress\GcpTables\Filters\TableFilterValueAccessor;
use PHPUnit\Framework\TestCase;



class TableFilterTest extends TestCase
{
 
    private static function makeEntry(array $values) : TableFilterValueAccessor{
        return new class($values) implements TableFilterValueAccessor{
            public function __construct(private array $values)
            {
                
            }
            function get(string $key): string|null{
                return isset($this->values[$key])? $this->values[$key] : null;
            }
        };
    }
    public function testCanParseAndReserialize(){
        $src = "'a' == 'a'";
        $foo = TableFilter::parse($src);
        $serialized =  $foo->__toString();
        $this->assertEquals($src,$serialized);
    }
    public function testCanUseInternalLogic(){
        $src = "'a' == 'a'";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }
    public function testCanUseAttributes(){
        $src = "attrib == 'a'";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry(['attrib'=>'a']));
        $this->assertTrue($actual);
    }

    public function testCanUseIn(){
        $src = "attrib in('a')";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry(['attrib'=>'a']));
        $this->assertTrue($actual);
    }
    public function testCanUseNotIn(){
        $src = "attrib not in('a')";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry(['attrib'=>'a']));
        $this->assertFalse($actual);
    }

    public function testCanUseIsNull(){
        $src = "attrib IS NULL";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry(['attrib'=>null]));
        $this->assertTrue($actual);
    }
    public function testCanUseIsNotNULL(){
        $src = "attrib IS NOT NULL";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry(['attrib'=>'a']));
        $this->assertTrue($actual);
    }
    public function testCanUseNot(){
        $src = "Not 'a' == 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

    public function testCanUseAndTrue(){
        $src = "'a' == 'a' and 'b'=='b'";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }
    public function testCanUseAndfalse(){
        $src = "'a' == 'a' and 'b'=='c'";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

    public function testCanUseOrTrue(){
        $src = "'a' == 'a' or 'b'=='c'";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }
    public function testCanUseOralse(){
        $src = "'a' == 'c' or 'b'=='c'";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

    public function testCanUseGtTrue(){
        $src = "'b' > 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }

    public function testCanUseGtFalse(){
        $src = "'a' > 'b' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

    public function testCanUseGteTrue_ForGreaterThan(){
        $src = "'b' >= 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }
    public function testCanUseGteTrue_ForEqual(){
        $src = "'a' >= 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }

    public function testCanUseGteFalse(){
        $src = "'a' >= 'b' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }
    public function testCanUseLtTrue(){
        $src = "'a'<'b' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }

    public function testCanUseLtFalse(){
        $src = "'b' < 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

    public function testCanUseLteTrue_ForLessThan(){
        $src = "'a' <= 'b' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }
    public function testCanUseLteTrue_ForEqual(){
        $src = "'a' <= 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }

    public function testCanUseLteFalse(){
        $src = "'b' <= 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

    public function testCanUseNeqFalse(){
        $src = "'a' <> 'a' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }
    public function testCanUseNeqTrue(){
        $src = "'a' <> 'b' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }



    public function testCanUseLikeTrue(){
        $src = "'abcde' like 'a%' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertTrue($actual);
    }
    public function testCanUseLikeFalse(){
        $src = "'abcde' like 'c%' ";
        $foo = TableFilter::parse($src);
        $actual =  $foo->__invoke(self::makeEntry([]));
        $this->assertFalse($actual);
    }

}
