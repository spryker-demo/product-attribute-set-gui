<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form;

use Generated\Shared\Transfer\ProductAttributeSetTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class ProductAttributeSetForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_PRODUCT_ATTRIBUTE_SET = 'idProductAttributeSet';

    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_PRODUCT_MANAGEMENT_ATTRIBUTE_IDS = 'productManagementAttributeIds';

    /**
     * @var string
     */
    public const OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_CHOICES = 'product_management_attribute_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_CHOICES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdProductAttributeSetField($builder)
            ->addNameField($builder)
            ->addProductManagementAttributeIdsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductAttributeSetField(FormBuilderInterface $builder)
    {
        $builder->add(ProductAttributeSetTransfer::ID_PRODUCT_ATTRIBUTE_SET, HiddenType::class, [
            'required' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(ProductAttributeSetTransfer::NAME, TextType::class, [
            'label' => 'product_attribute_set_gui.name',
            'constraints' => [
                new NotBlank(),
                new Length(['max' => 255]),
                $this->getFactory()->createUniqueNameConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addProductManagementAttributeIdsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(ProductAttributeSetTransfer::PRODUCT_MANAGEMENT_ATTRIBUTE_IDS, Select2ComboBoxType::class, [
            'label' => 'product_attribute_set_gui.attributes',
            'choices' => $options[static::OPTION_PRODUCT_MANAGEMENT_ATTRIBUTE_CHOICES],
            'multiple' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
