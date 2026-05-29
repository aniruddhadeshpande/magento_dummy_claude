<?php
/**
 * Blog post domain model.
 */
declare(strict_types=1);
namespace Study\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use Study\Blog\Api\Data\PostInterface;
use Study\Blog\Model\ResourceModel\Post as PostResource;

/**
 * ORM model for a blog post record stored in study_blog_post.
 */
class Post extends AbstractModel implements PostInterface
{

    /**
     * Binds this model to its resource model and sets the primary-key field.
     */
    protected function _construct()
    {
        $this->_init(PostResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @inheritDoc
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }
}
