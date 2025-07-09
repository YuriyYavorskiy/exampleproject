<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\DataProvider;

class AddCustomAttributesToIndex
{
    private CollectionFactory $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function afterGetData(DataProvider $subject, array $result): array
    {
        $productIds = array_keys($result);
        if (empty($productIds)) {
            return $result;
        }

        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect(['rating_summary']);
        $collection->addIdFilter($productIds);

        $collection->getSelect()->joinLeft(
            ['bestsellers' => $collection->getTable('sales_bestsellers_aggregated_monthly')],
            'e.entity_id = bestsellers.product_id',
            ['qty_ordered']
        );

        foreach ($collection as $product) {
            $id = (int)$product->getId();
            if (!isset($result[$id])) {
                continue;
            }

            $result[$id]['rating_summary'] = (int)$product->getData('rating_summary');
            $result[$id]['qty_ordered'] = (float)$product->getData('qty_ordered');
        }

        return $result;
    }
}
