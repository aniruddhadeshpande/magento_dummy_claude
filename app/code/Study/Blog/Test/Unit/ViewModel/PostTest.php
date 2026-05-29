<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Study\Blog\Api\Data\PostInterface;
use Study\Blog\Api\PostRepositoryInterface;
use Study\Blog\Model\ResourceModel\Post\Collection;
use Study\Blog\ViewModel\Post;

class PostTest extends TestCase
{
    private Post $viewModel;

    /** @var RequestInterface&MockObject */
    private $request;

    /** @var Collection&MockObject */
    private $collection;

    /** @var PostRepositoryInterface&MockObject */
    private $postRepository;

    protected function setUp(): void
    {
        $this->request = $this->getMockForAbstractClass(RequestInterface::class);
        $this->collection = $this->createMock(Collection::class);
        $this->postRepository = $this->getMockForAbstractClass(PostRepositoryInterface::class);

        $this->viewModel = new Post($this->request, $this->collection, $this->postRepository);
    }

    public function testGetListReturnsCollectionItems(): void
    {
        $items = [$this->getMockForAbstractClass(PostInterface::class)];
        $this->collection->expects($this->once())->method('getItems')->willReturn($items);

        $this->assertSame($items, $this->viewModel->getList());
    }

    public function testGetPostCountReturnsCollectionCount(): void
    {
        $this->collection->expects($this->once())->method('count')->willReturn(4);
        $this->assertSame(4, $this->viewModel->getPostCount());
    }

    public function testGetDetailByIdLoadsPostFromRequestId(): void
    {
        $post = $this->getMockForAbstractClass(PostInterface::class);
        $this->request->method('getParam')->with('id')->willReturn('11');
        $this->postRepository->expects($this->once())->method('getById')->with(11)->willReturn($post);

        $this->assertSame($post, $this->viewModel->getDetailById());
    }

    public function testGetDetailByIdPropagatesNotFound(): void
    {
        $this->request->method('getParam')->with('id')->willReturn('0');
        $this->postRepository->method('getById')->willThrowException(new NoSuchEntityException());

        $this->expectException(NoSuchEntityException::class);
        $this->viewModel->getDetailById();
    }
}
