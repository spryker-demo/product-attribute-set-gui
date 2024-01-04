<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAttributeSetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ATTRIBUTE_SET = 'id_product_attribute_set';

    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_ATTRIBUTE_SET_DELETED_SUCCESSFULLY = 'product_attribute_set_gui.product_attribute_set_successfully_deleted';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idProductAttributeSet = $this->castId($request->query->getInt(static::PARAM_ID_PRODUCT_ATTRIBUTE_SET));

        $productAttributeSetTransfer = new ProductAttributeSetTransfer();
        $productAttributeSetTransfer->setIdProductAttributeSet($idProductAttributeSet);

        $this->getFactory()->getProductAttributeSetFacade()->deleteProductAttributeSet($productAttributeSetTransfer);

        $this->addSuccessMessage(static::MESSAGE_PRODUCT_ATTRIBUTE_SET_DELETED_SUCCESSFULLY);

        return $this->redirectResponseExternal(IndexController::PRODUCT_ATTRIBUTE_SET_LIST_URL);
    }
}
