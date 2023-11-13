<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerDemo\Zed\ProductAttributeSetGui\Communication\ProductAttributeSetGuiCommunicationFactory getFactory()
 */
class ProductAttributeSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_SET = 'set';

    /**
     * @var string
     */
    public const FIELD_KEY_HIDDEN_ID = 'key_hidden_id';

    /**
     * @var string
     */
    public const ATTRIBUTE_SET_CHOICES = 'attribute_set_choices';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::ATTRIBUTE_SET_CHOICES)
            ->setDefaults([
                'required' => false,
            ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAttributeSetField($builder, $options[static::ATTRIBUTE_SET_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $choices
     *
     * @return $this
     */
    protected function addAttributeSetField(FormBuilderInterface $builder, array $choices)
    {
        $builder->add(static::FIELD_SET, ChoiceType::class, [
            'label' => 'product_attribute_set_gui.attribute_set',
            'placeholder' => 'product_attribute_set_gui.chose_attribute_set',
            'choices' => $choices,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'attribute_set_form';
    }
}
