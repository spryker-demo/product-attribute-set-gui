<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\Constraint;

use SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UniqueNameConstraint extends SymfonyConstraint
{
    /**
     * @var string
     */
    public const OPTION_PRODUCT_ATTRIBUTE_SET_FACADE = 'productAttributeSetFacade';

    /**
     * @var string
     */
    protected const MESSAGE = 'Product set with name "{{ name }}" already exists.';

    /**
     * @var \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface
     */
    protected ProductAttributeSetFacadeInterface $productAttributeSetFacade;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    /**
     * @return \SprykerDemo\Zed\ProductAttributeSet\Business\ProductAttributeSetFacadeInterface
     */
    public function getProductAttributeSetFacade(): ProductAttributeSetFacadeInterface
    {
        return $this->productAttributeSetFacade;
    }
}
