<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\ProductAttributeSetCriteriaTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueNameConstraintValidator extends ConstraintValidator
{
    /**
     * @param mixed|string $value
     * @param \Symfony\Component\Validator\Constraint|\SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\Constraint\UniqueNameConstraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueNameConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueNameConstraint::class);
        }

        /** @var \Generated\Shared\Transfer\ProductAttributeSetTransfer $productAttributeSetTransfer */
        $productAttributeSetTransfer = $this->context->getRoot()->getData();
        $productAttributeSetCriteriaTransfer = (new ProductAttributeSetCriteriaTransfer())->setName($value);

        if ($productAttributeSetTransfer->getIdProductAttributeSet()) {
            $productAttributeSetCriteriaTransfer->setExcludedProductAttributeSetId($productAttributeSetTransfer->getIdProductAttributeSet());
        }

        if (!$constraint->getProductAttributeSetFacade()->productAttributeSetExists($productAttributeSetCriteriaTransfer)) {
            return;
        }

        $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ name }}', $value)
                ->addViolation();
    }
}
