<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Study\Blog\Model\Post;
use Study\Blog\Model\PostFactory;
use Study\Blog\Model\PostRepository;
use Study\Blog\Model\ResourceModel\Post as ResourcePost;

class PostRepositoryTest extends TestCase
{
    private PostRepository $repository;

    /** @var PostFactory&MockObject */
    private $postFactory;

    /** @var ResourcePost&MockObject */
    private $postResource;

    /** @var Post&MockObject */
    private $post;

    protected function setUp(): void
    {
        $this->postFactory = $this->getMockBuilder(PostFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $this->postResource = $this->createMock(ResourcePost::class);
        $this->post = $this->getMockBuilder(Post::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMock();

        $this->repository = new PostRepository($this->postFactory, $this->postResource);
    }

    public function testGetByIdReturnsLoadedPost(): void
    {
        $id = 5;
        $this->postFactory->method('create')->willReturn($this->post);
        $this->postResource->expects($this->once())->method('load')->with($this->post, $id);
        $this->post->method('getId')->willReturn($id);

        $this->assertSame($this->post, $this->repository->getById($id));
    }

    public function testGetByIdThrowsWhenNotFound(): void
    {
        $this->postFactory->method('create')->willReturn($this->post);
        $this->postResource->expects($this->once())->method('load');
        $this->post->method('getId')->willReturn(null);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->getById(99);
    }

    public function testSaveReturnsPost(): void
    {
        $this->postResource->expects($this->once())->method('save')->with($this->post);
        $this->assertSame($this->post, $this->repository->save($this->post));
    }

    public function testSaveWrapsExceptionAsCouldNotSave(): void
    {
        $this->postResource->method('save')->willThrowException(new \Exception('db down'));
        $this->expectException(CouldNotSaveException::class);
        $this->repository->save($this->post);
    }

    public function testDeleteByIdReturnsTrue(): void
    {
        $id = 3;
        $this->postFactory->method('create')->willReturn($this->post);
        $this->post->method('getId')->willReturn($id);
        $this->postResource->expects($this->once())->method('delete')->with($this->post);

        $this->assertTrue($this->repository->deleteById($id));
    }

    public function testDeleteByIdWrapsExceptionAsCouldNotDelete(): void
    {
        $id = 3;
        $this->postFactory->method('create')->willReturn($this->post);
        $this->post->method('getId')->willReturn($id);
        $this->postResource->method('delete')->willThrowException(new \Exception('locked'));

        $this->expectException(CouldNotDeleteException::class);
        $this->repository->deleteById($id);
    }

    public function testDeleteByIdPropagatesNotFound(): void
    {
        $this->postFactory->method('create')->willReturn($this->post);
        $this->post->method('getId')->willReturn(null);

        $this->expectException(NoSuchEntityException::class);
        $this->repository->deleteById(404);
    }
}
