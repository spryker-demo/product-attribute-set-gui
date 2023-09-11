<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductAttributeSetTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\ProductAttributeSetForm;

class ProductAttributeSetFormDataProvider
{
    /**
     * @var \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface
     */
    protected ProductAttributeSetFacadeInterface $productAttributeSetFacade;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected ProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected LocaleFacadeInterface $localeFacade;

    /**
     * @param \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface $productAttributeSetFacade
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductAttributeSetFacadeInterface $productAttributeSetFacade,
        ProductAttributeFacadeInterface $productAttributeFacade,
        LocaleFacadeInterface $localeFacade
    ) {
        $this->productAttributeSetFacade = $productAttributeSetFacade;
        $this->productAttributeFacade = $productAttributeFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idProductAttributeSet
     *
     * @return array <string, mixed>
     */
    public function getData(?int $idProductAttributeSet = null): array
    {
        if ($idProductAttributeSet === null) {
            return [];
        }
        $data = [
            ProductAttributeSetForm::FIELD_ID_PRODUCT_ATTRIBUTE_SET => $idProductAttributeSet,
        ];
        $productAttributeSetTransfer = $this->getProductAttributeSetTransfer($idProductAttributeSet);

        $data += [
            ProductAttributeSetForm::FIELD_NAME => $productAttributeSetTransfer->getName(),
            ProductAttributeSetForm::FIELD_PRODUCT_MANAGEMENT_ATTRIBUTE_IDS => $productAttributeSetTransfer->getProductManagementAttributeIds(),
        ];

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        $options = [];

        $options[ProductAttributeSetForm::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_CHOICES] = $this->getProductManagementAttributeChoices();

        return $options;
    }

    /**
     * @param int $idProductAttributeSet
     *
     * @return \Generated\Shared\Transfer\ProductAttributeSetTransfer
     */
    protected function getProductAttributeSetTransfer(int $idProductAttributeSet): ProductAttributeSetTransfer
    {
        return $this->productAttributeSetFacade->findProductAttributeSetById($idProductAttributeSet) ?? new ProductAttributeSetTransfer();
    }

    /**
     * @return array<mixed, mixed>
     */
    protected function getProductManagementAttributeChoices(): array
    {
        $choices = [];
        $currentLocale = $this->localeFacade->getCurrentLocale();
        $productAttributes = $this->productAttributeFacade->getProductAttributeCollection();
        foreach ($productAttributes as $productAttribute) {
            foreach ($productAttribute->getLocalizedKeys() as $localizedKey) {
                if ($localizedKey->getLocaleName() === $currentLocale->getLocaleName()) {
                    $choices[$localizedKey->getKeyTranslation()] = $productAttribute->getIdProductManagementAttribute();

                    break;
                }
            }
        }

        return $choices;
    }
}
