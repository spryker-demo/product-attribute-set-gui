<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Zed\ProductAttributeSetGui\Communication\Extractor;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAttributeSetTransfer;

class LocalizedAttributeNamesExtractor implements LocalizedAttributeNamesExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAttributeSetTransfer $productAttributeSetTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    public function extractLocalizedAttributeNames(ProductAttributeSetTransfer $productAttributeSetTransfer, LocaleTransfer $localeTransfer): array
    {
        $productManagementAttributeNames = [];

        foreach ($productAttributeSetTransfer->getProductManagementAttributes() as $productManagementAttributeTransfer) {
            foreach ($productManagementAttributeTransfer->getLocalizedKeys() as $localizedKey) {
                if ($localeTransfer->getLocaleName() !== $localizedKey->getLocaleName() || $localizedKey->getKeyTranslation() === null) {
                    continue;
                }

                $productManagementAttributeNames[] = $localizedKey->getKeyTranslation();

                break;
            }
        }

        return $productManagementAttributeNames;
    }
}
