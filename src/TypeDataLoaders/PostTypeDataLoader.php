<?php

declare(strict_types=1);

namespace PoP\Posts\TypeDataLoaders;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoP\Posts\Facades\PostTypeAPIFacade;

class PostTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule(): ?array
    {
        return [
            \PoP_Posts_Module_Processor_FieldDataloads::class,
            \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_POSTLIST
        ];
    }

    public function getObjectQuery(array $ids): array
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return array(
            'include' => $ids,
            // If not adding the post types, WordPress only uses "post", so querying by CPT would fail loading data
            // This should be considered for the CMS-agnostic case if it makes sense
            'post-types' => $postTypeAPI->getPostTypes([
                'publicly-queryable' => true,
            ])
        );
    }

    public function getObjects(array $ids): array
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return $postTypeAPI->getPosts($query);
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array();
        $query['include'] = $ids;
        $query['post-status'] = [
            POP_POSTSTATUS_PUBLISHED,
            POP_POSTSTATUS_DRAFT,
            POP_POSTSTATUS_PENDING,
        ]; // Status can also be 'pending', so don't limit it here, just select by ID

        return $query;
    }

    public function executeQuery($query, array $options = [])
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPosts($query, $options);
    }

    protected function getOrderbyDefault()
    {
        return NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:posts:date');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQueryIds($query): array
    {
        $options = [
            'return-type' => POP_RETURNTYPE_IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }

    protected function getLimitParam($query_args)
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PostTypeDataLoader:query:limit',
            parent::getLimitParam($query_args)
        );
    }

    protected function getQueryHookName()
    {
        // Allow to add the timestamp for loadingLatest
        return 'PostTypeDataLoader:query';
    }
}
