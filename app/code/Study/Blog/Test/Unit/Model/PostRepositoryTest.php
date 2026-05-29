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

    /**
     * @dataProvider getByIdProvider
     */
    public function testGetById(int $id, ?int $loadedId, ?string $expectedException): void
    {
        $this->postFactory->method('create')->willReturn($this->post);
        $this->postResource->expects($this->once())->method('load')->with($this->post, $id);
        $this->post->method('getId')->willReturn($loadedId);

        if ($expectedException !== null) {
            $this->expectException($expectedException);
            $this->repository->getById($id);
            return;
        }

        $this->assertSame($this->post, $this->repository->getById($id));
    }

    public function getByIdProvider(): array
    {
        return [
            'found returns loaded post' => [5, 5, null],
            'not found throws NoSuchEntity' => [99, null, NoSuchEntityException::class],
        ];
    }

    /**
     * @dataProvider saveProvider
     */
    public function testSave(?\Exception $resourceException, ?string $expectedException): void
    {
        if ($resourceException !== null) {
            $this->postResource->method('save')->willThrowException($resourceException);
        } else {
            $this->postResource->expects($this->once())->method('save')->with($this->post);
        }

        if ($expectedException !== null) {
            $this->expectException($expectedException);
            $this->repository->save($this->post);
            return;
        }

        $this->assertSame($this->post, $this->repository->save($this->post));
    }

    public function saveProvider(): array
    {
        return [
            'success returns post' => [null, null],
            'wraps exception as CouldNotSave' => [new \Exception('db down'), CouldNotSaveException::class],
        ];
    }

    /**
     * @dataProvider deleteByIdProvider
     */
    public function testDeleteById(
        int $id,
        ?int $loadedId,
        ?\Exception $resourceException,
        ?string $expectedException
    ): void {
        $this->postFactory->method('create')->willReturn($this->post);
        $this->post->method('getId')->willReturn($loadedId);

        if ($resourceException !== null) {
            $this->postResource->method('delete')->willThrowException($resourceException);
        } elseif ($loadedId !== null) {
            $this->postResource->expects($this->once())->method('delete')->with($this->post);
        }

        if ($expectedException !== null) {
            $this->expectException($expectedException);
            $this->repository->deleteById($id);
            return;
        }

        $this->assertTrue($this->repository->deleteById($id));
    }

    public function deleteByIdProvider(): array
    {
        return [
            'success returns true' => [3, 3, null, null],
            'wraps exception as CouldNotDelete' => [3, 3, new \Exception('locked'), CouldNotDeleteException::class],
            'propagates not found from getById' => [404, null, null, NoSuchEntityException::class],
        ];
    }
}
