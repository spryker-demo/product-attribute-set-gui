<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form\Constraint;

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
        $productSetAttributeTransfer = $constraint->getProductAttributeSetFacade()->findProductAttributeSetByName($value);

        if (!$productSetAttributeTransfer) {
            return;
        }

        /** @var \Generated\Shared\Transfer\ProductAttributeSetTransfer $formDataProductAttributeSetTransfer */
        $formDataProductAttributeSetTransfer = $this->context->getRoot()->getData();

        if ($formDataProductAttributeSetTransfer['idProductAttributeSet'] !== null && $productSetAttributeTransfer->getIdProductAttributeSet() === (int)($formDataProductAttributeSetTransfer['idProductAttributeSet'])) {
            return;
        }
        $this->context->buildViolation($constraint->getMessage())
                ->setParameter('{{ name }}', $value)
                ->addViolation();
    }
}
