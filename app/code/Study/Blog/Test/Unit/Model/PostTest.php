<?php
declare(strict_types=1);

namespace Study\Blog\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Study\Blog\Api\Data\PostInterface;
use Study\Blog\Model\Post;

class PostTest extends TestCase
{
    private Post $model;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->model = $objectManager->getObject(Post::class);
    }

    public function testSetAndGetTitle(): void
    {
        $this->assertSame($this->model, $this->model->setTitle('Hello'));
        $this->assertSame('Hello', $this->model->getTitle());
        $this->assertSame('Hello', $this->model->getData(PostInterface::TITLE));
    }

    public function testSetAndGetContent(): void
    {
        $this->assertSame($this->model, $this->model->setContent('Body text'));
        $this->assertSame('Body text', $this->model->getContent());
        $this->assertSame('Body text', $this->model->getData(PostInterface::CONTENT));
    }

    public function testGetCreatedAt(): void
    {
        $this->model->setData(PostInterface::CREATED_AT, '2026-05-29 00:00:00');
        $this->assertSame('2026-05-29 00:00:00', $this->model->getCreatedAt());
    }

    public function testImplementsPostInterface(): void
    {
        $this->assertInstanceOf(PostInterface::class, $this->model);
    }
}
