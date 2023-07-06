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
class RenderFormController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function attributeSetAction(Request $request): array
    {
        $productAttributeSubFormDataProvider = $this->getFactory()->createProductAttributeSubFormDataProvider();
        $productAttributeSubForm = $this
            ->getFactory()
            ->getProductAttributeSubForm(
                $productAttributeSubFormDataProvider->getData(),
                $productAttributeSubFormDataProvider->getOptions(),
            )
            ->handleRequest($request);

        return $this->viewResponse([
            'form' => $productAttributeSubForm->createView(),
        ]);
    }
}
