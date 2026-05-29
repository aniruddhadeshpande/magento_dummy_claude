<?php
/**
 * Blog post view model — supplies post data to frontend templates.
 */
declare (strict_types = 1);
namespace Study\Blog\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Study\Blog\Api\Data\PostInterface;
use Study\Blog\Api\PostRepositoryInterface;
use Study\Blog\Model\ResourceModel\Post\Collection;

/**
 * Provides blog post data to PHTML templates injected via layout XML argument.
 */
class Post implements ArgumentInterface
{

    /**
     * @param RequestInterface $request
     * @param Collection $collection
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        private RequestInterface $request,
        private Collection $collection,
        private PostRepositoryInterface $postRepository,
    ) {
    }

    /**
     * Returns all blog posts from the collection.
     *
     * @return PostInterface[]
     */
    public function getList(): array
    {
        return $this->collection->getItems();
    }

    /**
     * Returns the total number of blog posts.
     *
     * @return int
     */
    public function getPostCount(): int
    {
        return $this->collection->count();
    }

    /**
     * Loads the post whose ID is in the current request's "id" parameter.
     *
     * @return PostInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException When no post matches the request ID.
     */
    public function getDetailById(): PostInterface
    {
        $id = (int)$this->request->getParam('id');
        return $this->postRepository->getById($id);
    }
}
