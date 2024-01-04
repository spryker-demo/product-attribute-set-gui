<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAttributeSetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_ATTRIBUTE_SET_CREATED_SUCCESSFULLY = 'product_attribute_set_gui.product_attribute_set_successfully_created';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createProductAttributeSetFormDataProvider();

        $form = $this
            ->getFactory()
            ->getProductAttributeSetForm(new ProductAttributeSetTransfer(), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFactory()->getProductAttributeSetFacade()->saveProductAttributeSet($form->getData());

            $this->addSuccessMessage(static::MESSAGE_PRODUCT_ATTRIBUTE_SET_CREATED_SUCCESSFULLY);

            return $this->redirectResponseExternal(IndexController::PRODUCT_ATTRIBUTE_SET_LIST_URL);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
