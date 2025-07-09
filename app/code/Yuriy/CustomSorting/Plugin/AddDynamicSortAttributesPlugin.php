<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Plugin;

use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AddDynamicSortAttributesPlugin
{
    private ScopeConfigInterface $scopeConfig;
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->attributeRepository = $attributeRepository;
    }

    public function afterToOptionArray($subject, array $result = []): array
    {
        return $this->mergeWithCustomOptions($result);
    }

    public function afterGetAllOptions($subject, array $result = []): array
    {
        return $this->mergeWithCustomOptions($result);
    }

    private function mergeWithCustomOptions(array $original): array
    {
        $custom = $this->getCustomOptions();
        $existingValues = array_column($original, 'value');

        foreach ($custom as $option) {
            if (!in_array($option['value'], $existingValues, true)) {
                $original[] = $option;
            }
        }

        return $original;
    }

    private function getCustomOptions(): array
    {
        $json = $this->scopeConfig->getValue(
            'customsorting/general/sort_fields',
            ScopeInterface::SCOPE_STORE
        );

        if (!$json) {
            return [];
        }

        $options = [];
        $data = json_decode($json, true);

        foreach ($data as $row) {
            if (!empty($row['attribute'])) {
                $label = $this->resolveAttributeLabel($row['attribute']);
                $options[] = [
                    'value' => $row['attribute'],
                    'label' => $label
                ];
            }
        }

        return $options;
    }

    private function resolveAttributeLabel(string $attributeCode): string
    {
        try {
            $attribute = $this->attributeRepository->get('catalog_product', $attributeCode);
            $label = $attribute->getFrontendLabel();

            if (is_string($label) && $label !== '') {
                return $label;
            }
        } catch (\Exception $e) {
            // ignore and fallback
        }

        return ucfirst(str_replace('_', ' ', $attributeCode));
    }
}
