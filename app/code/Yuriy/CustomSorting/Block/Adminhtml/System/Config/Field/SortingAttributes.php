<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Yuriy\CustomSorting\Block\Adminhtml\System\Config\Field\AttributeColumn;
use Yuriy\CustomSorting\Block\Adminhtml\System\Config\Field\SortOrderColumn;
use Magento\Framework\DataObject;

class SortingAttributes extends AbstractFieldArray
{
    protected $attributeRenderer;
    protected $sortOrderRenderer;

    protected function _prepareToRender(): void
    {
        $this->addColumn('attribute', [
            'label' => __('Product Attribute'),
            'class' => 'required-entry',
            'renderer' => $this->getAttributeRenderer()
        ]);
        $this->addColumn('sort_order', [
            'label' => __('Sort Order'),
            'class' => 'required-entry',
            'renderer' => $this->getSortOrderRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Attribute');
    }

    protected function getAttributeRenderer()
    {
        if (!$this->attributeRenderer) {
            $this->attributeRenderer = $this->getLayout()->createBlock(
                AttributeColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->attributeRenderer;
    }

    protected function getSortOrderRenderer()
    {
        if (!$this->sortOrderRenderer) {
            $this->sortOrderRenderer = $this->getLayout()->createBlock(
                SortOrderColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->sortOrderRenderer;
    }

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $attributeValue = $row->getData('attribute');
        $sortOrderValue = $row->getData('sort_order');

        if ($attributeValue !== null) {
            $options['option_extra_attr_' . $this->getAttributeRenderer()->calcOptionHash($attributeValue)] = 'selected="selected"';
        }

        if ($sortOrderValue !== null) {
            $options['option_extra_attr_' . $this->getSortOrderRenderer()->calcOptionHash($sortOrderValue)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}
