<?php
/**
 * Blog post resource model (database persistence layer).
 */

namespace Study\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Maps the Post domain model to the study_blog_post table.
 */
class Post extends AbstractDb
{

    /** @var string Primary database table for blog posts. */
    public const MAIN_TABLE= 'study_blog_post';

    /** @var string Primary-key column name. */
    public const ID_FIELD_NAME = 'id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
