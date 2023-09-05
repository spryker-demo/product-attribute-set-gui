/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('../../sass/_main.scss');

function castToBoolean($value) {
    return $value === 'true' || $value === '1' || $value === 1 || $value == 'true' || $value == true;
}

function AttributeManager() {
    var _attributeManager = {
        attributesValues: {},
        metaAttributes: {},
        locales: {},
        removedKeys: [],
    };

    var jsonLoader = {};

    jsonLoader.load = function (input) {
        var json = $(input).html();
        return JSON.parse(json);
    };

    _attributeManager.init = function () {
        _attributeManager.attributesValues = jsonLoader.load($('#productAttributesJson'));
        _attributeManager.metaAttributes = jsonLoader.load($('#metaAttributesJson'));
        _attributeManager.locales = jsonLoader.load($('#localesJson'));
    };

    _attributeManager.getLocaleCollection = function () {
        return _attributeManager.locales;
    };

    _attributeManager.extractKeysFromTable = function () {
        var keys = [];
        $('#productAttributesTable tr').each(function () {
            keys.push($(this).find('td:first').text().trim());
        });

        return keys;
    };

    _attributeManager.validateKey = function (key) {
        var currentKeys = _attributeManager.extractKeysFromTable();

        if ($.inArray(key, currentKeys) > -1) {
            alert('Attribute "' + key + '" already defined');
            return false;
        }
        var hasAttribute = false;
        $.ajax({
            url: '/product-attribute-gui/suggest/keys',
            dataType: 'json',
            async: false,
            data: {
                q: key,
            },
            success: function (data) {
                data = data.filter(function (value) {
                    return value.key == key;
                });
                if (data.length > 0) {
                    hasAttribute = true;
                }
            },
        });
        if (!hasAttribute) {
            alert('Attribute "' + key + '" doesn\'t exist.');

            return false;
        }

        return true;
    };

    _attributeManager.hasKeyBeenUsed = function (key) {
        var currentKeys = _attributeManager.extractKeysFromTable();

        return $.inArray(key, currentKeys) > 0;
    };

    _attributeManager.generateDataToAdd = function (key, idAttribute, attributeMetadata) {
        var dataToAdd = [];
        var locales = _attributeManager.getLocaleCollection();

        dataToAdd.push(key);

        for (var i in locales) {
            var localeData = locales[i];
            var readOnly = '';

            if (castToBoolean(attributeMetadata.is_super)) {
                readOnly = ' readonly="true" ';
            }

            var item =
                '<input type="' +
                attributeMetadata.input_type +
                '"' +
                ' class="spryker-form-autocomplete form-control ui-autocomplete-input kv_attribute_autocomplete" ' +
                ' data-allow_input="' +
                attributeMetadata.allow_input +
                '"' +
                ' data-is_super="' +
                attributeMetadata.is_super +
                '"' +
                ' data-is_attribute_input ' +
                ' data-attribute_key="' +
                key +
                '" ' +
                ' value="" ' +
                ' data-id_attribute="' +
                idAttribute +
                '" ' +
                ' data-locale_code="' +
                localeData['locale_name'] +
                '"' +
                readOnly +
                '>' +
                '<span style="display: none"></span>';

            dataToAdd.push(item);
        }

        dataToAdd.push(
            '<div style="text-align: left;"><a data-key="' +
            key +
            '" href="#" class="btn btn-xs btn-outline btn-danger remove-item">Remove</a></div>',
        );

        return dataToAdd;
    };

    _attributeManager.addSet = function (attributeMetadata, dataTable) {
        var currentKeys = _attributeManager.extractKeysFromTable();
        if ($.inArray(attributeMetadata.key, currentKeys) > -1) {
            alert('Attribute "' + attributeMetadata.key + '" already defined');
            return false;
        }

        _attributeManager.resetRemovedKey(attributeMetadata.key);

        var dataToAdd = _attributeManager.generateDataToAdd(attributeMetadata.key, attributeMetadata.id, attributeMetadata);

        dataTable.DataTable().row.add(dataToAdd).draw(true);

        updateAttributeInputsWithAutoComplete();
    };

    _attributeManager.addKey = function (key, idAttribute, dataTable) {
        key = key.replace(/([^a-z0-9\_\-\:]+)/gi, '').toLowerCase();

        if (key === '' || !idAttribute) {
            var $messageInput = $('#empty-attribute-key-message');

            alert($messageInput ? $messageInput.val() : 'Please select attribute key first');

            return false;
        }

        if (!_attributeManager.validateKey(key)) {
            return false;
        }

        var keyInput = $('#attribute_form_key');
        var attributeMetadata = {
            key: keyInput.attr('data-key'),
            id: keyInput.attr('data-value'),
            allow_input: castToBoolean(keyInput.attr('data-allow_input')),
            is_super: castToBoolean(keyInput.attr('data-is_super')),
            input_type: keyInput.attr('data-input_type'),
        };

        _attributeManager.resetRemovedKey(key);

        var dataToAdd = _attributeManager.generateDataToAdd(key, idAttribute, attributeMetadata);

        dataTable.DataTable().row.add(dataToAdd).draw(true);

        updateAttributeInputsWithAutoComplete();
    };

    _attributeManager.addRemovedKey = function (key) {
        _attributeManager.removedKeys.push(key);
    };

    _attributeManager.resetRemovedKey = function (key) {
        _attributeManager.removedKeys = _attributeManager.removedKeys.filter(function (removedKey) {
            return removedKey !== key;
        });
    };

    _attributeManager.resetRemovedKeysCache = function () {
        _attributeManager.removedKeys = [];
    };

    _attributeManager.save = function () {
        var locales = _attributeManager.getLocaleCollection();
        var form = $('form#attribute_values_form');
        var idProductAbstract = $('#attribute_values_form_hidden_product_abstract_id').val();
        var idProduct = $('#attribute_values_form_hidden_product_id').val();
        var csrfToken = $('#csrf-token').val();
        var formData = [];

        $('[data-is_attribute_input]').each(function (index, value) {
            var input = $(value);
            var attributeValue = input.val();
            var idAttribute = input.attr('data-id_attribute') || null;
            var locale_code = input.attr('data-locale_code') || null;
            var key = input.attr('data-attribute_key') || null;

            formData.push({
                key: key,
                id: idAttribute,
                locale_code: locale_code,
                value: attributeValue,
            });
        });

        $(_attributeManager.removedKeys).each(function (index, removedKey) {
            for (var i in locales) {
                var locale = locales[i];
                var localeName = locale['locale_name'];

                formData.push({
                    key: removedKey,
                    id: null,
                    locale_code: localeName,
                    value: '',
                });
            }
        });

        var formDataJson = JSON.stringify(formData);
        var actionUrl = form.attr('action');

        var actionData = {
            json: formDataJson,
            'id-product-abstract': idProductAbstract,
            'id-product': idProduct,
            'csrf-token': csrfToken,
        };

        $.ajax({
            url: actionUrl,
            type: 'POST',
            dataType: 'application/json',
            data: $.param(actionData),
            complete: function (jqXHR) {
                if (jqXHR.readyState === 4) {
                    _attributeManager.resetRemovedKeysCache();

                    $('#saveButton').prop('disabled', false).val('Save');

                    var message = 'An error has occurred';
                    var responseData = JSON.parse(jqXHR.responseText);
                    if (responseData.hasOwnProperty('message')) {
                        message = responseData.message;
                    }

                    window.sweetAlert({
                        title: jqXHR.status === 200 ? 'Success' : 'Error',
                        text: message,
                        type: jqXHR.status === 200 ? 'success' : 'error',
                    });

                    if (jqXHR.status === 200) {
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                }
            },
            beforeSend: function () {
                $('#saveButton').prop('disabled', true).val('Saving');
            },
        });
    };

    _attributeManager.init();

    return _attributeManager;
}

function removeActionHandler() {
    var $link = $(this);
    var dataTable = $('#productAttributesTable').DataTable();

    /*$link.parents('tr').find("td input").each(function(index, input) {
        $(input).val('');
    });
    $link.parents('tr').hide();*/

    dataTable.row($link.parents('tr')).remove().draw();

    return false;
}

function updateAttributeInputsWithAutoComplete() {
    $('[data-allow_input=""],[data-allow_input="false"],[data-allow_input="0"]').each(function (key, value) {
        var input = $(value);
        var is_super = castToBoolean(input.attr('data-is_super'));
        var is_read_only = castToBoolean(input.attr('data-is_read_only'));

        if (!is_super && !is_read_only) {
            input.on('focus click', function (event, ui) {
                $(this).autocomplete('search', '');
            });
        }
    });

    $('[data-is_attribute_input]').each(function (key, value) {
        var input = $(value);
        var id = input.attr('data-id_attribute') || null;
        var locale_code = input.attr('data-locale_code') || null;
        var is_read_only = castToBoolean(input.attr('data-is_read_only'));

        if (!is_read_only) {
            input.on('dblclick', function (event, ui) {
                $(this).autocomplete('search', '');
            });
        }

        input.autocomplete({
            minLength: 0,
            source: function (request, response) {
                $.ajax({
                    url: '/product-attribute-gui/attribute/suggest/',
                    dataType: 'json',
                    data: {
                        q: request.term,
                        id: id,
                        locale_code: locale_code,
                    },
                    success: function (data) {
                        response(
                            $.map(data.values, function (item) {
                                return {
                                    label: item.text,
                                    value: item.id,
                                };
                            }),
                        );
                    },
                });
            },
            change: function (event, ui) {
                var input = $(this);
                var value = input.val().trim();
                var selectedValue = ui.item ? ui.item.label : '';
                var allowInput = castToBoolean(input.attr('data-allow_input'));

                if (value === '') {
                    input.attr('data-value', '');
                    value = '';
                } else if (!allowInput) {
                    value = selectedValue;
                    input.attr('data-value', selectedValue);
                }

                input.val(value);
                input.attr('value', value);

                var span = input.parents('td').find('span');
                if (span) {
                    span.text(value);
                }
            },
            select: function (event, ui) {
                var input = $(this);
                input.val(ui.item.label);
                input.attr('data-value', ui.item.value);
                return false;
            },
            focus: function (event, ui) {
                var input = $(this);
                input.val(ui.item.label);
                input.attr('data-value', ui.item.value);
                return false;
            },
        });
    });
}

$(document).ready(function () {
    var attributeManager = new AttributeManager();
    var dataTable = $('#productAttributesTable');

    $('#add-attribute-set').on('click', function () {
        var input = $('#attribute_set_form_set');
        var key = input.val().trim();

        if (key === '0') {
            alert("Please select an attribute set");
        }

        $.ajax({
            url: `/product-attribute-set?attribute-set-id=${key}`,
            dataType: 'json',
            success: function (data) {
                data.product_management_attributes.forEach(function (attribute) {
                    attributeManager.addSet(
                        {
                            key: attribute.key,
                            id: attribute.id_product_management_attribute,
                            allow_input: castToBoolean(attribute.allow_input),
                            is_super: castToBoolean(attribute.is_super),
                            input_type: attribute.input_type,
                        },
                        dataTable
                    )
                });

                $('.remove-item')
                    .on('click', function (event, element) {
                        var key = $(this).attr('data-key');
                        attributeManager.addRemovedKey(key);
                        removeActionHandler.call($(this));
                        return false;
                    });
            }
        })


        return false;
    });
});
