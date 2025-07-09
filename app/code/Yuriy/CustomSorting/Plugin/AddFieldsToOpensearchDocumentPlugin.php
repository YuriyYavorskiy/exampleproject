<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Plugin;

use Magento\Elasticsearch\Model\Adapter\Elasticsearch;
use Magento\Framework\Search\RequestInterface;

class AddFieldsToOpensearchDocumentPlugin
{
    public function afterPrepareDocumentData(
        Elasticsearch $subject,
        array $result,
        array $documentData,
        RequestInterface $request
    ): array {
        if (isset($documentData['rating_summary'])) {
            $result['rating_summary'] = (int)$documentData['rating_summary'];
        }

        if (isset($documentData['qty_ordered'])) {
            $result['bestseller'] = (float)$documentData['qty_ordered'];
        }

        return $result;
    }
}
