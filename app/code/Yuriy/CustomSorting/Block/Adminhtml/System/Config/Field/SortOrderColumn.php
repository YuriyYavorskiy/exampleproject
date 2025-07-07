<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Block\Adminhtml\System\Config\Field;

use Magento\Framework\View\Element\Html\Select;

class SortOrderColumn extends Select
{
    protected function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSortOptions());
        }
        return parent::_toHtml();
    }

    private function getSortOptions(): array
    {
        return [
            ['label' => 'Ascending (Up)', 'value' => 'asc'],
            ['label' => 'Descending (Down)', 'value' => 'desc'],
        ];
    }
}
