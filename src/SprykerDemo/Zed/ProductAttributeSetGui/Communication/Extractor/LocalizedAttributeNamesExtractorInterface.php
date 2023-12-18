<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Extractor;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAttributeSetTransfer;

interface LocalizedAttributeNamesExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAttributeSetTransfer $productAttributeSetTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    public function extractLocalizedAttributeNames(ProductAttributeSetTransfer $productAttributeSetTransfer, LocaleTransfer $localeTransfer): array;
}
