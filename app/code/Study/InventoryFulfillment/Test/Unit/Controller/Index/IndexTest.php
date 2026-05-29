<?php
declare(strict_types=1);

namespace Study\InventoryFulfillment\Test\Unit\Controller\Index;

use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Study\InventoryFulfillment\Controller\Index\Index;

class IndexTest extends TestCase
{
    public function testExecuteRendersPageWithShippingPlanTitle(): void
    {
        $title = $this->createMock(Title::class);
        $title->expects($this->once())->method('set')->with('Shipping Plan');

        $config = $this->createMock(Config::class);
        $config->expects($this->once())->method('getTitle')->willReturn($title);

        $page = $this->createMock(Page::class);
        $page->expects($this->once())->method('getConfig')->willReturn($config);

        $pageFactory = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $pageFactory->expects($this->once())->method('create')->willReturn($page);

        $controller = new Index($pageFactory);

        $this->assertSame($page, $controller->execute());
    }
}
