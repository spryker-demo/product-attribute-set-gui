<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ATTRIBUTE_SET = 'id_product_attribute_set';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $idProductAttributeSet = $this->castId($request->query->getInt(static::PARAM_ID_PRODUCT_ATTRIBUTE_SET));

        $productAttributeSetTransfer = $this->getFactory()
            ->getProductAttributeSetFacade()
            ->findProductAttributeSetById($idProductAttributeSet);

        if (!$productAttributeSetTransfer) {
            $this->addErrorMessage('Product attribute set was not found.');

            return $this->redirectResponseExternal(IndexController::PRODUCT_ATTRIBUTE_SET_LIST_URL);
        }

        $productManagementAttributesNames = $this->getFactory()
            ->createLocalizedAttributeNamesExtractor()
            ->extractLocalizedAttributeNames($productAttributeSetTransfer, $this->getFactory()->getLocaleFacade()->getCurrentLocale());

        return [
            'productAttributeSet' => $productAttributeSetTransfer,
            'productManagementAttributesNames' => $productManagementAttributesNames,
        ];
    }
}
