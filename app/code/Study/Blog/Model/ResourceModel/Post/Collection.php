<?php
/**
 * Blog post collection.
 */

namespace Study\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Study\Blog\Model\Post;
use Study\Blog\Model\ResourceModel\Post as ResourcePostCollection;

/**
 * Loads sets of Post models from study_blog_post via the standard collection API.
 */
class Collection extends AbstractCollection
{
    /**
     * Binds the collection to its model and resource model classes.
     */
    protected function _construct()
    {
        $this->_init(Post::class, ResourcePostCollection::class);
    }
}
