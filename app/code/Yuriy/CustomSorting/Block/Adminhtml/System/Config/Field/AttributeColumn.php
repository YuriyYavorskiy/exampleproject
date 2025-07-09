<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Html\Select;

class AttributeColumn extends Select
{
    protected function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getAttributeOptions());
        }

        $this->setName($this->getInputName());
        $this->setId($this->getHtmlId());

        return parent::_toHtml();
    }

    private function getAttributeOptions(): array
    {
        return [
            ['label' => 'Name', 'value' => 'name'],
            ['label' => 'Price', 'value' => 'price'],
            ['label' => 'Special Price', 'value' => 'special_price'],
            ['label' => 'Bestsellers', 'value' => 'bestseller'],
            ['label' => 'Position', 'value' => 'position'],
            ['label' => 'Rating Summary', 'value' => 'rating_summary'],
        ];
    }
}
