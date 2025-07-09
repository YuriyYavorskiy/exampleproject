<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Plugin;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Category;
use Magento\Framework\App\RequestInterface;

class AddFrontendSortingOptionsPlugin
{
    private RequestInterface $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function afterGetAttributeUsedForSortByArray(Config $subject, array $result): array
    {
        if (php_sapi_name() === 'cli' || $this->request->getFullActionName() !== 'catalog_category_view') {
            return $result;
        }

        $customFields = [
            'bestseller' => __('Best Sellers'),
            'rating_summary' => __('Rating Summary'),
        ];

        foreach ($customFields as $code => $label) {
            if (!is_string($code) || trim($code) === '') {
                continue;
            }
            $label = (string) $label;
            if (trim($label) === '') {
                continue;
            }
            if (!array_key_exists($code, $result)) {
                $result[$code] = $label;
            }
        }

        // Убираем случайно попавшие пустые ключи
        foreach ($result as $key => $val) {
            if (trim((string)$key) === '' || trim((string)$val) === '') {
                unset($result[$key]);
            }
        }

        return $result;
    }
}
