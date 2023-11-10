<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAttributeSetTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class AttributeSetController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ATTRIBUTE_SET = 'id_product_attribute_set';

    /**
     * @var string
     */
    protected const PRODUCT_ATTRIBUTE_SET_LIST_URL = '/product-attribute-set-gui/attribute-set/list';

    /**
     * @return array<string, string>
     */
    public function listAction(): array
    {
        $table = $this->getFactory()->createProductAttributeSetTable();

        return [
            'productAttributeSetTable' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function listTableAction(): JsonResponse
    {
        $table = $this->getFactory()->createProductAttributeSetTable();

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createProductAttributeSetFormDataProvider();

        $form = $this
            ->getFactory()
            ->getProductAttributeSetForm($dataProvider->getData(), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleProductAttributeSetForm($form);

            $this->addSuccessMessage('Product attribute set successfully created');

            return $this->redirectResponse(
                Url::generate(static::PRODUCT_ATTRIBUTE_SET_LIST_URL)->build(),
            );
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function editAction(Request $request)
    {
        $idProductAttributeSet = $this->castId($request->query->getInt(static::PARAM_ID_PRODUCT_ATTRIBUTE_SET));

        $dataProvider = $this
            ->getFactory()
            ->createProductAttributeSetFormDataProvider();

        $form = $this
            ->getFactory()
            ->getProductAttributeSetForm($dataProvider->getData($idProductAttributeSet), $dataProvider->getOptions())
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleProductAttributeSetForm($form);
            $this->addSuccessMessage('Product attribute set successfully updated');

            return $this->redirectResponse(
                Url::generate(static::PRODUCT_ATTRIBUTE_SET_LIST_URL)->build(),
            );
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request): RedirectResponse
    {
        $idProductAttributeSet = $this->castId($request->query->getInt(static::PARAM_ID_PRODUCT_ATTRIBUTE_SET));

        $productAttributeSetTransfer = new ProductAttributeSetTransfer();
        $productAttributeSetTransfer->setIdProductAttributeSet($idProductAttributeSet);

        $this->getFactory()->getProductAttributeSetFacade()->deleteProductAttributeSet($productAttributeSetTransfer);

        $this->addSuccessMessage('Product attribute set successfully deleted');

        return $this->redirectResponse(
            Url::generate(static::PRODUCT_ATTRIBUTE_SET_LIST_URL)->build(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function viewAction(Request $request)
    {
        $idProductAttributeSet = $this->castId($request->query->getInt(static::PARAM_ID_PRODUCT_ATTRIBUTE_SET));

        $productAttributeSetTransfer = $this->getFactory()
            ->getProductAttributeSetFacade()
            ->findProductAttributeSetById($idProductAttributeSet);

        if (!$productAttributeSetTransfer) {
            return $this->redirectResponse(
                Url::generate(static::PRODUCT_ATTRIBUTE_SET_LIST_URL)->build(),
            );
        }

        $productManagementAttributesNames = $this->getFactory()->getProductAttributeSetFacade()->getProductManagementAttributeNames($productAttributeSetTransfer);

        return [
            'productAttributeSet' => $productAttributeSetTransfer,
            'productManagementAttributesNames' => $productManagementAttributesNames,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function handleProductAttributeSetForm(FormInterface $form): void
    {
        $productAttributeSetTransfer = new ProductAttributeSetTransfer();
        $productAttributeSetTransfer->fromArray($form->getData(), true);

        $this->getFactory()->getProductAttributeSetFacade()->saveProductAttributeSet($productAttributeSetTransfer);
    }
}
