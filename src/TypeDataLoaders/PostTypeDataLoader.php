<?php
namespace PoP\Posts\TypeDataLoaders;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;

class PostTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getDataquery()
    {
        return GD_DATAQUERY_POST;
    }

    public function getFilterDataloadingModule(): ?array
    {
        return [\PoP_Posts_Module_Processor_FieldDataloads::class, \PoP_Posts_Module_Processor_FieldDataloads::MODULE_DATALOAD_DATAQUERY_POSTLIST_FIELDS];
    }

    public function getObjectQuery(array $ids): array
    {
        return array(
            'include' => $ids,
        );
    }

    public function getObjects(array $ids): array
    {
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        $query = $this->getObjectQuery($ids);
        return $cmspostsapi->getPosts($query);
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
        $cmspostsapi = \PoP\Posts\FunctionAPIFactory::getInstance();
        return $cmspostsapi->getPosts($query, $options);
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
