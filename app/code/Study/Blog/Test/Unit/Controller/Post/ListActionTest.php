<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Controller\Post;

use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Study\Blog\Controller\Post\ListAction;

class ListActionTest extends TestCase
{
    public function testExecuteReturnsCreatedPage(): void
    {
        $page = $this->createMock(Page::class);
        $pageFactory = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $pageFactory->expects($this->once())->method('create')->willReturn($page);

        $controller = new ListAction($pageFactory);
        $this->assertSame($page, $controller->execute());
    }
}
