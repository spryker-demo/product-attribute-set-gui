<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication;

use Generated\Shared\Transfer\ProductAttributeSetTransfer;
use Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSetQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Extractor\LocalizedAttributeNamesExtractor;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Extractor\LocalizedAttributeNamesExtractorInterface;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\Constraint\UniqueNameConstraint;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\DataProvider\ProductAttributeSetFormDataProvider;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\DataProvider\ProductAttributeSubFormDataProvider;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\ProductAttributeSetForm;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\ProductAttributeSubForm;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Table\ProductAttributeSetTable;
use SprykerDemo\Zed\ProductAttributeSetGui\ProductAttributeSetGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class ProductAttributeSetGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerDemo\Zed\ProductAttributeSetGui\Communication\Table\ProductAttributeSetTable
     */
    public function createProductAttributeSetTable(): ProductAttributeSetTable
    {
        return new ProductAttributeSetTable(
            $this->getProductAttributeSetQuery(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @return \Orm\Zed\ProductAttributeSet\Persistence\SpyProductAttributeSetQuery
     */
    public function getProductAttributeSetQuery(): SpyProductAttributeSetQuery
    {
        return $this->getProvidedDependency(ProductAttributeSetGuiDependencyProvider::PROPEL_QUERY_PRODUCT_ATTRIBUTE_SET);
    }

    /**
     * @return \SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\DataProvider\ProductAttributeSetFormDataProvider
     */
    public function createProductAttributeSetFormDataProvider(): ProductAttributeSetFormDataProvider
    {
        return new ProductAttributeSetFormDataProvider(
            $this->getProductAttributeSetFacade(),
            $this->getProductAttributeFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeSetTransfer $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductAttributeSetForm(ProductAttributeSetTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductAttributeSetForm::class, $data, $options);
    }

    /**
     * @return \SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\DataProvider\ProductAttributeSubFormDataProvider
     */
    public function createProductAttributeSubFormDataProvider(): ProductAttributeSubFormDataProvider
    {
        return new ProductAttributeSubFormDataProvider(
            $this->getProductAttributeSetFacade(),
        );
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductAttributeSubForm(array $data = [], array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ProductAttributeSubForm::class, $data, $options);
    }

    /**
     * @return \SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\Constraint\UniqueNameConstraint
     */
    public function createUniqueNameConstraint(): UniqueNameConstraint
    {
        return new UniqueNameConstraint(
            [UniqueNameConstraint::OPTION_PRODUCT_ATTRIBUTE_SET_FACADE => $this->getProductAttributeSetFacade()],
        );
    }

    /**
     * @return \SprykerDemo\Zed\ProductAttributeSetGui\Communication\Extractor\LocalizedAttributeNamesExtractorInterface
     */
    public function createLocalizedAttributeNamesExtractor(): LocalizedAttributeNamesExtractorInterface
    {
        return new LocalizedAttributeNamesExtractor();
    }

    /**
     * @return \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface
     */
    public function getProductAttributeSetFacade(): ProductAttributeSetFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeSetGuiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE_SET);
    }

    /**
     * @return \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeSetGuiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    public function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeSetGuiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    public function getTranslatorFacade(): TranslatorFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeSetGuiDependencyProvider::FACADE_TRANSLATOR);
    }
}
