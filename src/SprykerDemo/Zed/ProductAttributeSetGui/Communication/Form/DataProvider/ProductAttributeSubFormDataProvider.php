<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\DataProvider;

use SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface;
use SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\ProductAttributeSubForm;

class ProductAttributeSubFormDataProvider
{
    /**
     * @var \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface
     */
    protected ProductAttributeSetFacadeInterface $attributeSetFacade;

    /**
     * @param \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface $attributeSetFacade
     */
    public function __construct(ProductAttributeSetFacadeInterface $attributeSetFacade)
    {
        $this->attributeSetFacade = $attributeSetFacade;
    }

    /**
     * @return array<mixed>
     */
    public function getData(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            ProductAttributeSubForm::ATTRIBUTE_SET_CHOICES => $this->getAttributeSetChoices(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getAttributeSetChoices(): array
    {
        $attributeSets = $this->attributeSetFacade->getProductAttributeSets();
        $choices = [
            'Choose an attribute set' => null,
        ];

        foreach ($attributeSets as $attributeSet) {
            $choices[$attributeSet->getName()] = $attributeSet->getIdProductAttributeSet();
        }

        return $choices;
    }
}
