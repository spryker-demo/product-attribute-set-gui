<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Table;

use Orm\Zed\ProductAttributeSet\Persistence\Map\SpyProductAttributeSetTableMap;
use Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSet;
use Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSetQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;

class ProductAttributeSetTable extends AbstractTable
{
    /**
     * @var string
     */
    public const TABLE_COL_ACTIONS = 'Actions';

    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ATTRIBUTE_SET = 'id_product_attribute_set';

    /**
     * @var \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSetQuery
     */
    protected SpyProductAttributeSetQuery $productAttributeSetQuery;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    protected TranslatorFacadeInterface $translatorFacade;

    /**
     * @param \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSetQuery $productAttributeSetQuery
     * @param \Spryker\Zed\Translator\Business\TranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        SpyProductAttributeSetQuery $productAttributeSetQuery,
        TranslatorFacadeInterface $translatorFacade
    ) {
        $this->productAttributeSetQuery = $productAttributeSetQuery;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setUrl('table');
        $config->setHeader([
            SpyProductAttributeSetTableMap::COL_ID_PRODUCT_ATTRIBUTE_SET => 'ID',
            SpyProductAttributeSetTableMap::COL_NAME => 'Name',
            static::TABLE_COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            SpyProductAttributeSetTableMap::COL_NAME,
        ]);

        $config->setSortable([
            SpyProductAttributeSetTableMap::COL_ID_PRODUCT_ATTRIBUTE_SET,
            SpyProductAttributeSetTableMap::COL_NAME,
        ]);

        $config->setDefaultSortField(SpyProductAttributeSetTableMap::COL_ID_PRODUCT_ATTRIBUTE_SET, TableConfiguration::SORT_DESC);
        $config->addRawColumn(static::TABLE_COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $result = [];

        /** @var array<\Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSet> $queryResult */
        $queryResult = $this->runQuery($this->productAttributeSetQuery, $config, true);

        foreach ($queryResult as $productAttributeSetEntity) {
            $result[] = [
                SpyProductAttributeSetTableMap::COL_ID_PRODUCT_ATTRIBUTE_SET => $productAttributeSetEntity->getIdProductAttributeSet(),
                SpyProductAttributeSetTableMap::COL_NAME => $productAttributeSetEntity->getName(),
                static::TABLE_COL_ACTIONS => $this->getActionButtons($productAttributeSetEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSet $productAttributeSetEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyProductAttributeSet $productAttributeSetEntity): string
    {
        $buttons = [];
        $buttons[] = $this->createViewButton($productAttributeSetEntity);
        $buttons[] = $this->createEditButton($productAttributeSetEntity);
        $buttons[] = $this->createDeleteButton($productAttributeSetEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSet $productAttributeSetEntity
     *
     * @return string
     */
    protected function createViewButton(SpyProductAttributeSet $productAttributeSetEntity): string
    {
        $url = sprintf('product-attribute-set-gui/view?%s=%d', static::PARAM_ID_PRODUCT_ATTRIBUTE_SET, $productAttributeSetEntity->getIdProductAttributeSet());

        return $this->generateViewButton($url, 'View');
    }

    /**
     * @param \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSet $productAttributeSetEntity
     *
     * @return string
     */
    protected function createEditButton(SpyProductAttributeSet $productAttributeSetEntity): string
    {
        $url = sprintf('product-attribute-set-gui/edit?%s=%d', static::PARAM_ID_PRODUCT_ATTRIBUTE_SET, $productAttributeSetEntity->getIdProductAttributeSet());

        return $this->generateEditButton($url, 'Edit');
    }

    /**
     * @param \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSet $productAttributeSetEntity
     *
     * @return string
     */
    protected function createDeleteButton(SpyProductAttributeSet $productAttributeSetEntity): string
    {
        $url = sprintf('product-attribute-set-gui/delete?%s=%d', static::PARAM_ID_PRODUCT_ATTRIBUTE_SET, $productAttributeSetEntity->getIdProductAttributeSet());

        return $this->generateRemoveButton($url, $this->translatorFacade->trans('Delete'), [
            'title' => $this->translatorFacade->trans('Deletion Warning'),
            'text' => $this->translatorFacade->trans('Deleting Will Permanently Remove the Item'),
            'confirm-button-text' => $this->translatorFacade->trans('Delete'),
            AbstractTable::DELETE_FORM_NAME_SUFFIX => '_modal_confirm',
        ]);
    }
}
