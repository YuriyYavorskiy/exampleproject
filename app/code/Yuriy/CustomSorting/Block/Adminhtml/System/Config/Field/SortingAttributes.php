<?php
declare(strict_types=1);

namespace Yuriy\CustomSorting\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class SortingAttributes extends AbstractFieldArray
{
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

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $attribute = $row->getData('attribute');
        $sortOrder = $row->getData('sort_order');

        if ($attribute !== null) {
            $options['option_' . $this->getAttributeRenderer()->calcOptionHash($attribute)] = 'selected="selected"';
        }

        if ($sortOrder !== null) {
            $options['option_' . $this->getSortOrderRenderer()->calcOptionHash($sortOrder)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    protected function getAttributeRenderer()
    {
        if (!$this->getData('attribute_renderer')) {
            $layout = $this->getLayout();
            $renderer = $layout->createBlock(
                AttributeColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->setData('attribute_renderer', $renderer);
        }
        return $this->getData('attribute_renderer');
    }

    protected function getSortOrderRenderer()
    {
        if (!$this->getData('sort_order_renderer')) {
            $layout = $this->getLayout();
            $renderer = $layout->createBlock(
                SortOrderColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->setData('sort_order_renderer', $renderer);
        }
        return $this->getData('sort_order_renderer');
    }
}
