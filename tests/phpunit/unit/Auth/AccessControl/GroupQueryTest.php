<?php
namespace ImboUnitTest\Auth\AccessControl;

use Imbo\Auth\AccessControl\GroupQuery;
use PHPUnit\Framework\TestCase;

/**
 * @covers Imbo\Auth\AccessControl\GroupQuery
 * @covers Imbo\Auth\AccessControl\AbstractQuery
 * @group unit
 */
class GroupQueryTest extends TestCase {
    /**
     * @var GroupQuery
     */
    private $query;

    /**
     * Set up
     */
    public function setUp() : void {
        $this->query = new GroupQuery();
    }

    /**
     * @covers Imbo\Auth\AccessControl\AbstractQuery::limit
     */
    public function testSetAndGetLimit() {
        $this->assertSame(20, $this->query->limit());
        $this->assertSame($this->query, $this->query->limit(10));
        $this->assertSame(10, $this->query->limit());
    }

    /**
     * @covers Imbo\Auth\AccessControl\AbstractQuery::page
     */
    public function testSetAndGetPage() {
        $this->assertSame(1, $this->query->page());
        $this->assertSame($this->query, $this->query->page(2));
        $this->assertSame(2, $this->query->page());
    }
}
