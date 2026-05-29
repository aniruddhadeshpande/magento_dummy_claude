<?php
/**
 * Blog post repository implementation.
 */

namespace Study\Blog\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Study\Blog\Api\Data\PostInterface;
use Study\Blog\Api\PostRepositoryInterface;
use Study\Blog\Model\ResourceModel\Post as ResourcePost;

/**
 * Concrete CRUD implementation for blog posts.
 */
class PostRepository implements PostRepositoryInterface
{

    /**
     * @param PostFactory $postFactory
     * @param ResourcePost $postResource
     */
    public function __construct(
        private PostFactory $postFactory,
        private ResourcePost $postResource
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): PostInterface
    {
        $post = $this->postFactory->create();
        $this->postResource->load($post, $id);
        if (!$post->getId()) {
            throw new NoSuchEntityException(__('Post with id "%d" could not be found.', $id));
        }
        return $post;
    }

    /**
     * @inheritDoc
     */
    public function save(PostInterface $post): PostInterface
    {
        try {
            $this->postResource->save($post);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $id): bool
    {
        $post = $this->getById($id);
        try {
            $this->postResource->delete($post);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        }

        return true;
    }
}
