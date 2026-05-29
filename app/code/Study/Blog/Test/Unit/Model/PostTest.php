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

    /**
     * @dataProvider setAndGetProvider
     */
    public function testSetAndGet(string $setter, string $getter, string $dataKey, string $value): void
    {
        $this->assertSame($this->model, $this->model->{$setter}($value));
        $this->assertSame($value, $this->model->{$getter}());
        $this->assertSame($value, $this->model->getData($dataKey));
    }

    public function setAndGetProvider(): array
    {
        return [
            'title' => ['setTitle', 'getTitle', PostInterface::TITLE, 'Hello'],
            'content' => ['setContent', 'getContent', PostInterface::CONTENT, 'Body text'],
        ];
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
