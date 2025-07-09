<?php
namespace Yuriy\CustomSorting\Plugin;

use Magento\CatalogSearch\Model\Search\RequestGenerator;
use Magento\Framework\Api\SortOrder;

class AddCustomSortOrders
{
    public function afterBuild(
        RequestGenerator $subject,
        array $result
    ): array {
        $sortOrders = $subject->getSortOrders();
        foreach ($sortOrders as $sortOrder) {
            if ($sortOrder->getField() === 'bestseller') {
                $result['sort'][0] = ['bestseller' => ['order' => strtolower($sortOrder->getDirection())]];
            }
            if ($sortOrder->getField() === 'rating_summary') {
                $result['sort'][0] = ['rating_summary' => ['order' => strtolower($sortOrder->getDirection())]];
            }
        }

        return $result;
    }
}
