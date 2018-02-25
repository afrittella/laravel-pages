<?php

use Afrittella\LaravelPages\Domain\Model\Page;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /** @var Page */
    private $pageModel;

    protected function setUp()
    {
        $this->pageModel = $this->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->setMethods(['getRelatedSlugs'])
            ->getMock();

        parent::setUp();
    }

    public function testCanSlug(): void
    {
        $title = 'This is a title';
        $expectedSlug = 'this-is-a-title';

        $this->pageModel->expects($this->once())
             ->method('getRelatedSlugs')
             ->willReturn(new Collection());

        $this->assertEquals($expectedSlug, $this->pageModel->createSlug($title, 0));
    }

    public function testDuplicateSlug(): void
    {
        $title = 'This is a title';
        $expectedSlug = 'this-is-a-title-1';

        $this->pageModel->expects($this->once())
             ->method('getRelatedSlugs')
             ->willReturn(new Collection(
                 [
                    [
                        'id' => 1,
                        'slug' => 'this-is-a-title',
                        'title' => 'This is a title',
                        'code' => 'page1',
                        '_lft' => 1,
                        '_rgt' => 2,
                        'parent_id' => null
                    ]
                 ]
             ));

        $this->assertEquals($expectedSlug, $this->pageModel->createSlug($title, 2));
    }
}