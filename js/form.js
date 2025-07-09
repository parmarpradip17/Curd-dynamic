// Replace these with your PHP variables if you're rendering from server:
function setupAutocomplete(inputId, dropdownId, dataList, addContainerId, addInputId) {
    const input = $('#' + inputId);
    const dropdown = $('#' + dropdownId);
    const addContainer = $('#' + addContainerId);
    const addInput = $('#' + addInputId);

    input.on('input focus', function () {
        const term = input.val().toLowerCase().trim();
        const matches = dataList.filter(item => item.toLowerCase().includes(term));
        dropdown.empty();

        if (matches.length > 0) {
            matches.forEach(match => {
                dropdown.append('<div>' + match + '</div>');
            });
            dropdown.show();
        } else {
            dropdown.hide();
        }

        if (term === 'others') {
            addContainer.show();
            addInput.val('');
        } else {
            addContainer.hide();
        }
    });

    dropdown.on('click', 'div', function () {
        const selected = $(this).text();
        input.val(selected);
        dropdown.hide();

        if (selected.toUpperCase() === 'OTHERS') {
            addContainer.show();
            addInput.val('');
        } else {
            addContainer.hide();
        }
    });

    input.on('blur', function () {
        setTimeout(() => {
            const val = input.val().trim().toUpperCase();
            if (val === 'OTHERS') {
                addContainer.show();
                addInput.val('');
            } else {
                addContainer.hide();
            }
        }, 200);
    });

    $(document).on('click', function (e) {
        if (!dropdown.is(e.target) && !input.is(e.target) && !dropdown.has(e.target).length) {
            dropdown.hide();
        }
    });
}

function setupMultiSelect(inputId, dropdownId, selectedContainerId, hiddenInputId, dataList) {
    const input = $('#' + inputId);
    const dropdown = $('#' + dropdownId);
    const selected = $('#' + selectedContainerId);
    const hidden = $('#' + hiddenInputId);
    let selectedValues = [];

    input.on('input focus', function () {
        const term = input.val().toLowerCase();
        const matches = dataList.filter(item => item.toLowerCase().includes(term) && !selectedValues.includes(item));
        dropdown.empty();

        if (matches.length > 0) {
            matches.forEach(match => {
                dropdown.append('<div>' + match + '</div>');
            });
            dropdown.show();
        } else {
            dropdown.hide();
        }
    });

    dropdown.on('click', 'div', function () {
        const val = $(this).text();
        if (!selectedValues.includes(val)) {
            selectedValues.push(val);
            selected.append('<span class="badge bg-primary me-1">' + val + '</span>');
            hidden.val(selectedValues.join(', '));
        }
        input.val('');
        dropdown.hide();
    });

    $(document).on('click', function (e) {
        if (!dropdown.is(e.target) && !input.is(e.target) && !dropdown.has(e.target).length) {
            dropdown.hide();
        }
    });
}

function showAddOption(inputId, containerId, addInputId, buttonId, tableName) {
    const input = $('#' + inputId);
    const container = $('#' + containerId);
    const addInput = $('#' + addInputId);
    const button = $('#' + buttonId);

    input.on('blur', function () {
        setTimeout(() => {
            const val = input.val().trim();
            const exists = tableName === 'qualifications' ? qualifications.includes(val) : hobbies.includes(val);
            if (val && !exists) {
                container.show();
                addInput.val(val);
            } else {
                container.hide();
            }
        }, 200);
    });

    button.on('click', function () {
        const value = addInput.val().trim();
        if (value !== '') {
            $.post('add_extra_value.php', {
                value: value,
                type: tableName
            }, function (response) {
                alert(response.message);
                if (response.success) {
                    if (tableName === 'qualifications') {
                        qualifications.push(value);
                        $('#quali_input').val(value);
                    } else {
                        hobbies.push(value);
                        $('#selected_hobbies').append('<span class="badge bg-primary me-1">' + value + '</span>');
                        const existing = $('#hobbies_final').val();
                        $('#hobbies_final').val(existing ? existing + ', ' + value : value);
                    }
                    container.hide();
                }
            }, 'json');
        }
    });
}

// Initialize on document ready
$(document).ready(function () {
    setupAutocomplete('quali_input', 'quali_dropdown', qualifications, 'quali_add_container', 'quali_add_input');
    setupMultiSelect('hobbies_input', 'hobbies_dropdown', 'selected_hobbies', 'hobbies_final', hobbies);
    showAddOption('quali_input', 'quali_add_container', 'quali_add_input', 'quali_add_btn', 'qualifications');
    showAddOption('hobbies_input', 'hobby_add_container', 'hobby_add_input', 'hobby_add_btn', 'hobbies');
});
