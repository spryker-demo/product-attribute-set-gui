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
class EditController extends AbstractController
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

        $dataProvider = $this
            ->getFactory()
            ->createProductAttributeSetFormDataProvider();

        $productAttributeSet = $this->getFactory()->getProductAttributeSetFacade()->findProductAttributeSetById($idProductAttributeSet);

        if ($productAttributeSet === null) {
            $this->addErrorMessage('Product attribute set was not found.');

            return $this->redirectResponseExternal(IndexController::PRODUCT_ATTRIBUTE_SET_LIST_URL);
        }

        $form = $this
            ->getFactory()
            ->getProductAttributeSetForm($productAttributeSet, $dataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getFactory()->getProductAttributeSetFacade()->saveProductAttributeSet($form->getData());
            $this->addSuccessMessage('Product attribute set successfully updated');

            return $this->redirectResponseExternal(IndexController::PRODUCT_ATTRIBUTE_SET_LIST_URL);
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
