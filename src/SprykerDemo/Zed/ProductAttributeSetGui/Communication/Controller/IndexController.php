<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    public const PRODUCT_ATTRIBUTE_SET_LIST_URL = '/product-attribute-set-gui';

    /**
     * @return array<string, string>
     */
    public function indexAction(): array
    {
        $table = $this->getFactory()->createProductAttributeSetTable();

        return [
            'productAttributeSetTable' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()->createProductAttributeSetTable();

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAttributeSetDataAction(Request $request): JsonResponse
    {
        $attributeSetId = $this->castId($request->query->get('attribute-set-id'));

        if (!$attributeSetId) {
            return $this->jsonResponse(['error' => 'Attribute set id is required'], 400);
        }

        $attributeSet = $this->getFactory()->getProductAttributeSetFacade()->findProductAttributeSetById($attributeSetId);

        if (!$attributeSet) {
            return $this->jsonResponse(['error' => 'Attribute set not found'], 404);
        }

        return $this->jsonResponse(
            $attributeSet->toArray(),
        );
    }
}
