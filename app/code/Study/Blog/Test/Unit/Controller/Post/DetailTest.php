<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Controller\Post;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Study\Blog\Controller\Post\Detail;

class DetailTest extends TestCase
{
    public function testExecuteDispatchesEventAndReturnsPage(): void
    {
        $page = $this->createMock(Page::class);
        $pageFactory = $this->getMockBuilder(PageFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $pageFactory->expects($this->once())->method('create')->willReturn($page);

        $request = $this->getMockForAbstractClass(RequestInterface::class);

        $manager = $this->getMockForAbstractClass(EventManagerInterface::class);
        $manager->expects($this->once())
            ->method('dispatch')
            ->with('blog_study_post_detail', ['request' => $request]);

        $controller = new Detail($pageFactory, $request, $manager);
        $this->assertSame($page, $controller->execute());
    }
}
