// subproject-table-collapse

function initializeSelect2(element, click_employee_row, random, type_id) {
    let serach_type = null;

    let modal_user_type = "add_user_project";

    let line_item_country = "line_item_country";

    if (type_id == 1) {
        serach_type = "Employee";

        modal_user_type = "add_user_project";

        line_item_country = "line_item_country";
    } else if (type_id == 2) {
        serach_type = "Consultant";

        modal_user_type = "add_consultant_project";

        line_item_country = "line_item_country1";
    } else if (type_id == 3) {
        serach_type = "Sub-grantee";

        modal_user_type = "add_sub_grantee_project";

        line_item_country = "line_item_country2";
    } else if (type_id == 6) {
        serach_type = "other direct expense";
        // modal_user_type = 'add_other_direct_expense_project'
        line_item_country = "line_item_country3";
    }

    $(
        ".js-example-basic-single-" + click_employee_row + "_" + random
    ).select2();

    $("#select_project_user_" + click_employee_row + "_" + random)
        .select2({
            width: "100%",
            allowClear: true,
            closeOnSelect: true,
            placeholder: "Select " + serach_type,
            dropdownCssClass: "bigdrop",
        })
        .on("select2:open", function () {
            let select2Instance = $(this).data("select2");
            if (!$(".select2-heading").length) {
                let $heading = $(
                    '<div class="select2-heading">Select ' +
                        serach_type +
                        "</div>"
                );

                select2Instance.$dropdown
                    .find(".select2-search")
                    .before($heading);

                let $searchInput = select2Instance.$dropdown.find(
                    ".select2-search input"
                );
                $searchInput.attr("placeholder", "Search " + serach_type + "");
                if (type_id != 6) {
                    select2Instance.$dropdown
                        .find(".select2-search")
                        .append(
                            '<button class="select2-link btn btn-primary theme-btn flex-shrink-0 ' +
                                line_item_country +
                                '" data-bs-toggle="modal" data-bs-target="#' +
                                modal_user_type +
                                '"><img src="/images/icons/plus.svg" alt="add-icon" class="me-2 "/>Add new</button>'
                        );
                }
            }
        })
        .on("click", ".select2-search", function (e) {
            // Check if the clicked element is the button
            if ($(e.target).hasClass("select2-link")) {
                // Button click event handler
                alert("Button clicked!");
            }
        });
}

$(document).on("click", ".table-detail-acordian", function () {
    let collapse = $(this).attr("data-id");

    $("#" + collapse).toggleClass("collapsed");
});

$(document).on("click", ".delete-row-alert .btn-cencel", function () {
    $(".delete-row-alert").removeClass("d-flex");
});

let click_employee_row = 0;

let increaseNumber = 1;

$(document).on("click", ".add_employee_row", function () {
    var previousTr = $(this).closest("tr").prev("tr");

    let project_id = $(this).attr("data-project-id");
    let year = $(this).attr("data-year");

    let type_id = $(this).attr("data-type-id");

    let sub_project_id = $(this).attr("data-sub-project-id");

    click_employee_row = previousTr.children("td:first").text();

    if (!$.isNumeric(click_employee_row)) {
        click_employee_row = 0;
    }

    click_employee_row++;

    let findtable = $(this).closest("table").attr("id");

    let detailsrow = $("#" + findtable)
        .find("tbody")
        .find(".append-row").length;

    if (detailsrow >= 0) {
        $(this).prop("disabled", true);
    }

    if (detailsrow >= 1) {
        return false;
    }

    $("." + findtable).attr("style", "display:none !important");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    if (type_id != 6) {
        $.ajax({
            url: "/get-user-project",
            method: "GET",
            data: {
                project_id: project_id,
                user_type_id: type_id,
                sub_project_id: sub_project_id,
                year: year,
            },
            success: function (response) {
                processUsers(response, type_id, year);
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }
    if (type_id == 6) {
        $.ajax({
            url: "/get-other-direct-expenses",
            method: "GET",
            data: {
                project_id: project_id,
                sub_project_id: sub_project_id,
                year: year,
            },
            success: function (response) {
                processUsers(response, type_id, year);
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }

    let id = $(this).attr("data-id");

    var button = $(".tr_" + id);

    function processUsers(users, type_id, year) {
        if (type_id == 4) {
            serach_type = "Travel";
        } else if (type_id == 5) {
            serach_type = "Meeting";
        } else if (type_id == 5) {
            serach_type = "Other direct expense";
        }

        let random = randomNumber(1, 1000000);
        let optionsHtml = "";
        users.user.forEach((user, index) => {
            optionsHtml += `<option value="${user.id}">${user.name}</option>`;
        });

        let newRow = $(`
  <tr class="details-row append-row targetElement_${id}" data-appended-row id="targetElement_${click_employee_row}">
    <td style="text-align:center;">${click_employee_row}</td>
    <td class="editable-td p-0">
    ${
        type_id == 1 || type_id == 2 || type_id == 3 || type_id == 6
            ? `
      <select class="w-100 js-example-basic-single-${click_employee_row}_${random} select_project_user_${click_employee_row}_${random} table-select employee_data rows_${increaseNumber}_${year}_employee" data-user-type-id="${type_id}" data-id="${increaseNumber}_${year}" data-year="${year}" wire:model="rows.${click_employee_row}.employee" id="select_project_user_${click_employee_row}_${random}" wire:change="setCurrentEmployee($event.target.value)" required>
      <option value="">Select Line Item</option>
      ${optionsHtml}
      </select>
      `
            : `<input type="text" class="table-input rows_${increaseNumber}_${year}_employee" wire:model="rows.${click_employee_row}.employee" value="${
                  serach_type + " " + click_employee_row
              }" placeholder="${serach_type + " " + click_employee_row}"/>`
    }

      <div id="rows_${increaseNumber}_${year}_employee_error" class="text-error-500 project-validation-error rows_${increaseNumber}_${year}_employee_error"></div>
    </td>
    <td class="editable-td p-0" wire:ignore>

        <select class="w-100 js-example-basic-single-${click_employee_row}_${random}  table-select rows_${increaseNumber}_${year}_note" data-minimum-results-for-search="Infinity" wire:model="rows.${click_employee_row}.note" id="js-example-basic-single-${click_employee_row}_${random}">

            <option value="per_day">Per day</option>
            <option value="per_night">Per night</option>
            <option value="per_year">Per year</option>
            <option value="per_month">Per month</option>
            <option value="per_page">Per page</option>
            <option value="per_item">Per item</option>
            <option value="per_trip">Per trip</option>
            <option value="per_event">Per event</option>
            <option value="per_partner">Per partner</option>
        </select>
    </td>
    <td class="editable-td p-0" wire:ignore>
        <input type="text" class="table-input project_unit rows_${increaseNumber}_${year}_unit" data-type='currency' placeholder="0" min="1" max="100" data-id="${increaseNumber}_${year}" id="rows_${click_employee_row}_unit" wire:model="rows.${click_employee_row}.unit" />

        <div id="rows_${increaseNumber}_${year}_project_unit_error" class="text-error-500 project-validation-error rows_${increaseNumber}_${year}_project_unit_error"></div>
    </td>
   ${
       type_id == 1 || type_id == 2
           ? ` <td id="rows_${click_employee_row}_unitcost"> €<span class="rows_${increaseNumber}_${year}_unitcost">0</span></td>`
           : ` <td id="rows_${click_employee_row}_unitcost" class="editable-td p-0"><span class="currency-sign">€</span><input type="text" class="table-input project_expenses_input project-expenses-input rows_${increaseNumber}_${year}_unitcost unit_cost" placeholder="0" data-type='currency' data-id="${increaseNumber}_${year}"  /><div id="rows_${increaseNumber}_${year}_unit_cost_error" class="text-error-500 project-validation-error rows_${increaseNumber}_${year}_unit_cost_error"></div>`
   }</td>
    <td id="rows_${click_employee_row}_approval_budget">€<span class="rows_${increaseNumber}_${year}_approval_budget">0</span></td>
    <td class="editable-td p-0" wire:ignore>
      <span class="currency-sign">€</span>
      <input type="text" class="table-input project_expenses project_expenses_input project-expenses-input rows_${increaseNumber}_${year}_expenses"  data-id="${increaseNumber}_${year}" placeholder="0" min="1" max="100"  wire:model="rows.${click_employee_row}.expenses" id="rows_${click_employee_row}_expenses" data-type='currency' />

        <div id="rows_${click_employee_row}_project_expenses_error" class="text-error-500 project-validation-error rows_${increaseNumber}_${year}_project_expenses_error"></div>
    </td>
    <td id="rows_${click_employee_row}_remaining_balance">
    €<span class="rows_${increaseNumber}_${year}_remaining_balance">0</span>
    </td>
    <td align="center" class="action-toolbar">
      <div class="action-btns button-loader">
        <a type="button" class="me-2 delete_project_row" data-remove="targetElement_${click_employee_row}">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" stroke="#F04438" stroke-width="1.5" stroke-miterlimit="10"></path>
            <path d="M12.5 7.5L7.5 12.5" stroke="#F04438" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M12.5 12.5L7.5 7.5" stroke="#F04438" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </a>
        <a type="button" class="correct-row" wire:click="save(${click_employee_row})" data-correct-row="${type_id}" data-click-employee-row="${increaseNumber}_${year}" data-year="${year}">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M13.4375 8.125L8.85156 12.5L6.5625 10.3125" stroke="#12B76A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M10 17.5C14.1421 17.5 17.5 14.1421 17.5 10C17.5 5.85786 14.1421 2.5 10 2.5C5.85786 2.5 2.5 5.85786 2.5 10C2.5 14.1421 5.85786 17.5 10 17.5Z" stroke="#12B76A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </a>
      </div>
    </td>
  </tr>
  `);

        newRow.insertBefore(button);

        initializeSelect2(newRow, click_employee_row, random, type_id);

        increaseNumber++;

        bindCurrencyFormatter(newRow);
    }
});

function bindCurrencyFormatter(row) {
    row.find('input[data-type="currency"]').each(function () {
        new AutoNumeric(this, {
            digitGroupSeparator: ".",
            decimalCharacter: ",",
            decimalPlaces: 2,
            minimumValue: "0",
            maximumValue: "9999999999999999999999999999.99",
            modifyValueOnWheel: false,
            allowDecimalPadding: true,
            alwaysAllowDecimalCharacter: true,
            currencySymbolPlacement: "p",
            reverse: true,
            selectOnFocus: false, // Disable selection on focus
        });
    });
}

//  end subproject-table-collapse

// select 2
$(document).ready(function () {
    $(".js-example-basic-single").select2();
    $("#add_donor").on("shown.bs.modal", function () {
        $(".js-example-basic-single").select2({
            dropdownParent: $(".modal-body"),
        });
        $(".select_country_modal").select2({
            dropdownParent: $(".modal-body"),
        });
    });
    $("#add_user_project").on("shown.bs.modal", function () {
        $(".js-example-basic-single").each(function () {
            // Find the closest .line-item-body relative to the current .js-example-basic-single
            var lineItemBody = $(this).closest(".line-item-body");
            // Initialize Select2 within the found .line-item-body
            $(this).select2({
                dropdownParent: lineItemBody,
            });
        });
    });
    $(".select2ww")
        .select2({
            width: "100%",
            allowClear: true,
            closeOnSelect: true,
            placeholder: "Select project donor",
            ajax: {
                url: "/get-donor-project", // Replace with your AJAX endpoint
                method: "GET",
                dataType: "json",
                processResults: function (response) {
                    var data = $.map(response, function (obj) {
                        obj.id = obj.id;
                        obj.text = obj.name; // Use 'text' property for display text
                        return obj;
                    });
                    return {
                        results: data,
                    };
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            },
        })
        .on("select2:open", function () {
            let select2Instance = $(this).data("select2");
            if (!$(".select2-heading").length) {
                let $heading = $(
                    '<div class="select2-heading">Select donor</div>'
                );
                select2Instance.$dropdown
                    .find(".select2-search")
                    .before($heading);
            }

            if (!$(".select2-link").length) {
                let $searchInput = select2Instance.$dropdown.find(
                    ".select2-search input"
                );
                $searchInput.attr("placeholder", "Search donor");

                select2Instance.$results
                    .parents(".select2-dropdown--below")
                    .find(".select2-search")
                    .append(
                        '<button class="select2-link btn btn-primary theme-btn flex-shrink-0 international" data-bs-toggle="modal" data-bs-target="#add_donor"><img src="/images/icons/plus.svg" alt="add-icon" class="me-2 "/>Add new</button>'
                    );
            }
        })
        .on("click", ".select2-search", function (e) {
            // Check if the clicked element is the button
            if ($(e.target).hasClass("select2-link")) {
                // Button click event handler
                alert("Button clicked!");
            }
        });

    $(".select_project_user")
        .select2({
            width: "100%",
            allowClear: true,
            closeOnSelect: true,
            placeholder: "Select Line Item",
            dropdownCssClass: "bigdrop",
        })
        .on("select2:open", function () {
            let select2Instance = $(this).data("select2");
            if (!$(".select2-heading").length) {
                let $heading = $(
                    '<div class="select2-heading">Select Line Item</div>'
                );
                select2Instance.$dropdown
                    .find(".select2-search")
                    .before($heading);
                let $searchInput = select2Instance.$dropdown.find(
                    ".select2-search input"
                );
                $searchInput.attr("placeholder", "Select Line Item");
                select2Instance.$results
                    .parents(".select2-dropdown--below")
                    .find(".select2-search")
                    .append(
                        '<button class="select2-link btn btn-primary theme-btn flex-shrink-0" data-bs-toggle="modal" data-bs-target="#add_user_project"><img src="/images/icons/plus.svg" alt="add-icon" class="me-2 "/>Add new</button>'
                    );
            }
        })
        .on("click", ".select2-search", function (e) {
            // Check if the clicked element is the button
            if ($(e.target).hasClass("select2-link")) {
                // Button click event handler
                alert("Button clicked!");
            }
        });

    //datepicker
    $(".datepicker2").datepicker({
        format: "dd-mm-yyyy",
        // startDate: '0d'
    });

    $("#searchInput").on("keyup", function () {
        let value = $(this).val().toLowerCase();

        searchSubProject(value);
    });

    $("#clearButton").on("click", function () {
        let value = $(this).val().toLowerCase();
        $("#searchInput").val("");
        searchSubProject(value);
    });

    function searchSubProject(value) {
        var noDataFound = true; // Assume no data is found initially

        $(".accordion-item").each(function () {
            var text = $(this).text().toLowerCase();
            var matches = text.indexOf(value) > -1;

            // Toggle the accordion item based on whether it matches the search or not
            $(this).toggle(matches);

            // If there is at least one matching item, set noDataFound to false
            if (matches) {
                noDataFound = false;
            }
        });

        $(".nav-tabs .serach-result-nav").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });

        // Check if any search fields are visible
        if (noDataFound) {
            $(".no-subproject-found").attr(
                "style",
                "display: flex;align-items: center;justify-content: center;"
            ); // Assuming you have an element with the ID "noDataFoundMessage"
        } else {
            $(".no-subproject-found").attr("style", "display:none !important"); // Assuming you have an element with the ID "noDataFoundMessage"
        }
    }

    $(".js-example-basic-single").each(function () {
        var id = $(this).attr("id");

        let usertypeIid = $(this).attr("data-user-type-id");

        let line_item_country = "line_item_country";

        let modal_user_type = "add_user_project";

        if (usertypeIid != undefined) {
            if (usertypeIid == 1) {
                line_item_country = "line_item_country";

                modal_user_type = "add_user_project";
            } else if (usertypeIid == 2) {
                line_item_country = "line_item_country1";

                modal_user_type = "add_consultant_project";
            } else if (usertypeIid == 3) {
                line_item_country = "line_item_country2";

                modal_user_type = "add_sub_grantee_project";
            }
        }

        $('[data-select22="' + id + '"]')
            .select2({
                width: "100%",
                allowClear: true,
                closeOnSelect: true,
                placeholder: "Select Option",
            })
            .on("select2:open", function () {
                let select2Instance = $(this).data("select2");
                if (!$(".select2-heading").length) {
                    let $heading = $(
                        '<div class="select2-heading">Select Option</div>'
                    );
                    select2Instance.$dropdown
                        .find(".select2-search")
                        .before($heading);
                }

                if (!$(".select2-link").length) {
                    let $searchInput = select2Instance.$dropdown.find(
                        ".select2-search input"
                    );
                    $searchInput.attr("placeholder", "Search Option");
                    if (usertypeIid != undefined && usertypeIid != 6) {
                        select2Instance.$results
                            .parents(".select2-dropdown--below")
                            .find(".select2-search")
                            .append(
                                '<button class="select2-link btn btn-primary theme-btn flex-shrink-0 ' +
                                    line_item_country +
                                    '" data-bs-toggle="modal" data-bs-target="#' +
                                    modal_user_type +
                                    '"><img src="/images/icons/plus.svg" alt="add-icon" class="me-2 "/>Add new</button>'
                            );
                        $(".select2-heading").parent().addClass("bigdrop");
                    }
                }
            })
            .on("click", ".select2-search", function (e) {
                // Check if the clicked element is the button
                if ($(e.target).hasClass("select2-link")) {
                    // Button click event handler
                    alert("Button clicked!");
                }
            });
    });

    //datepicker revised and date prepared
    $(".date_prepare_datepicker").datepicker({
        format: "dd-mm-yyyy",
    });

    $(".date_revised_datepicker").datepicker({
        format: "dd-mm-yyyy",
    });

    // current budget timeline from and current budget timeline to
    $(".date_pickercurrent_budget_timeline_from")
        .datepicker({
            format: "dd-mm-yyyy",
            startDate: "0d",
        })
        .on("change", function (e) {
            let selectedDate = e.target.value;

            $(".date_picker_current_budget_timeline_to").datepicker(
                "setStartDate",
                selectedDate
            );
            $(".date_picker_current_budget_timeline_to").datepicker(
                "setDatesDisabled",
                [selectedDate]
            );
        });

    $(".date_picker_current_budget_timeline_to").datepicker({
        format: "dd-mm-yyyy",
    });

    // project duration from and project duration  to
    $(".datepicker_project_duration_from")
        .datepicker({
            format: "dd-mm-yyyy",
            startDate: "0d",
        })
        .on("change", function (e) {
            let selectedDate = e.target.value;

            $(".datepicker_project_duration_to").datepicker(
                "setStartDate",
                selectedDate
            );
            $(".datepicker_project_duration_to").datepicker(
                "setDatesDisabled",
                [selectedDate]
            );
        });

    $(".datepicker_project_duration_to").datepicker({
        format: "dd-mm-yyyy",
    });
});
let oldValue = null;
$(".datepicker2").on("focus", function () {
    oldValue = $(this).val();
});

$(".datepicker2").on("focusout", function () {
    const currentValue = $(this).val();

    // Check if the date has changed
    if (currentValue !== oldValue) {
        $(this).val(currentValue);
    }
});

$(".datepicker2").on("keypress", function (e) {
    const currentValue = $(this).val();

    if (e.which === 13) {
        e.preventDefault();
        $(this).val(currentValue);
    }
});

let Countryoldvalue = null;
$(".phone_modal").on("focus", function () {
    Countryoldvalue = $(this).val();
});

$(".phone_modal").on("focusout", function () {
    const currentValue = $(this).val();
    // Check if the date has changed
    if (currentValue !== Countryoldvalue) {
        $(this).val(Countryoldvalue);
    } else {
        $(this).val(currentValue);
    }
});

//get the date from select2 send into component
document.addEventListener("livewire:load", function () {
    $(".js-example-basic-single").select2();

    /** select box for create project  */
    $(".js-example-basic-single").on("change", function (e) {
        if ($(this).attr("wire:model") == "project_type") {
            Livewire.emit(
                "updateProjectType",
                e.target.value,
                "project_type",
                e.target.value
            );
        }
        if ($(this).attr("wire:model") == "project_code") {
            Livewire.emit("updateProjectCode", e.target.value);
        } else if ($(this).attr("wire:model") == "confirm_w_finance") {
            Livewire.emit(
                "updateconfirmWfinance",
                e.target.value,
                "confirm_w_finance",
                e.target.value
            );
        } else if ($(this).attr("wire:model") == "currency") {
            Livewire.emit(
                "updateCurrency",
                e.target.value,
                "currency",
                e.target.value
            );
        } else if ($(this).attr("wire:model") == "name") {
            Livewire.emit("updateName", e.target.value);
        } else if ($(this).attr("wire:model") == "employee") {
            Livewire.emit("updateEmployee", e.target.value);
        } else if ($(this).attr("wire:model") == "sub_project_id") {
            Livewire.emit("updateSubProject", e.target.value);
        } else if ($(this).attr("wire:model") == "project_donor_id") {
            Livewire.emit("updateprojectDonorId", e.target.value);
            Livewire.emit("updatedProjectDonorId", e.target.value);
            Livewire.emit(
                "editprojectDonorId",
                $("#project_create_user").val()
            );
            internationalCountry(".phone_modal");
        } else if ($(this).attr("wire:model") == "user_type") {
            Livewire.emit("updateUserType", e.target.value);
        } else if ($(this).attr("wire:model") == "country") {
            Livewire.emit(
                "updateCountry",
                e.target.value,
                "country",
                e.target.value
            );
        } else if ($(this).attr("wire:model") == "recordsPerPage") {
            Livewire.emit("updateRecordsPerPage", e.target.value);
        } else if ($(this).attr("wire:model") == "status") {
            Livewire.emit("updateprojectStatus", e.target.value);
        } else if ($(this).attr("wire:model") == "role") {
            Livewire.emit("updateRole", e.target.value);
        } else if ($(this).attr("wire:model") == "country_rate") {
            Livewire.emit("updatecountryRate", e.target.value);
        } else if ($(this).attr("data-input") == "notes") {
            var expenseId = $(this).parent().find(".expenseId").val();

            var categoryId = $(this).parent().find(".categoryId").val();
            var rate = $(".OdUnitCost" + expenseId + categoryId).val();
            rate = rate == undefined ? 0 : rate;
            Livewire.emit(
                "updateOdSelectedValue",
                "notes",
                e.target.value,
                expenseId,
                categoryId,
                rate
            );
        } else if ($(this).attr("data-input") == "employee-notes") {
            var employeeId = $(this).parent().find(".employeeId").val();
            var categoryId = $(this).parent().find(".categoryId").val();
            var rate = $(this)
                .parent()
                .find(".IeUnitCost" + employeeId + categoryId)
                .val();
            Livewire.emit(
                "updateIESelectedValue",
                "notes",
                e.target.value,
                employeeId,
                categoryId,
                rate
            );
        } else if ($(this).attr("wire:model") == "donor_name") {
            Livewire.emit("updateDonorName", e.target.value);
        }

        // let saveButton = $(this).siblings(".save-pill");
        // if (saveButton) {
        //     saveButton.click();
        //     $(this).blur();
        // }
    });

    //for project create
    Livewire.on("swal:alert", function (val) {
        // const loader = document.createElement('div');
        // loader.id = 'loader';
        // document.body.appendChild(loader);

        // const removeLoader = () => {
        //   loader.remove();
        // };

        setTimeout(() => {
            showSwalToast();
        }, 400);

        function showSwalToast() {
            Swal.fire({
                toast: true,
                title: val.title,
                text: val.text,
                animation: false,
                position: "bottom",
                showConfirmButton: false,
                timer: 2000,
                position: "top-right",
                timerProgressBar: true,
                customClass: {
                    popup: val.status == "success" ? "success" : "error",
                },
                html:
                    `
            <div class="custom-toast">
                    <span class="custom-toast-close"><img src="/../images/icons/alert-cross-icon.svg" alt="cross"></span>
                    <div class="custom-toast-content">

                <p>` +
                    val.text +
                    `</p>
              </div>
            </div>`,
                onAfterClose: function () {
                    // Redirect when the dialog is closed

                    if (val.text != "Donor Created Successfully!") {
                        if (val.redirectUrl) {
                            window.location.href = val.redirectUrl;
                        }
                    } else {
                        //removeLoader();

                        $(".select-project-type-create").select2();
                    }
                },
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });
        }
    });

    //for donor create
    Livewire.on("swal:alert:donor", function (val) {
        setTimeout(() => {
            showSwalToast();
        }, 400);

        function showSwalToast() {
            Swal.fire({
                toast: true,
                title: val.title,
                text: val.text,
                animation: false,
                position: "bottom",
                showConfirmButton: false,
                timer: 2000,
                position: "top-right",
                timerProgressBar: true,
                customClass: {
                    popup: val.status == "success" ? "success" : "error",
                },
                html:
                    `
            <div class="custom-toast">
                    <span class="custom-toast-close"><img src="/../images/icons/alert-cross-icon.svg" alt="cross"></span>
                    <div class="custom-toast-content">

                <p>` +
                    val.text +
                    `</p>
              </div>
            </div>`,
                onAfterClose: function () {
                    // Redirect when the dialog is closed

                    if (val.redirectUrl) {
                        window.location.href = val.redirectUrl;
                    }
                },
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });
        }
    });

    Livewire.on("hideSaveButton", function (id) {
        $(".save-table-btn").hide();
    });

    //for livwire swal error
    Livewire.on("error", function () {
        Swal.fire({
            type: "error",
            title: "Error",
            text: "Some thing went wrong",
            allowOutsideClick: false, // Prevents closing the dialog by clicking outside
            allowEscapeKey: false, // Prevents closing the dialog by pressing Esc key
            showConfirmButton: true,
        });
    });

    $(".modal").on("hidden.bs.modal", function () {
        $("form")[0].reset();
    });

    Livewire.on("close-modal", () => {
        Livewire.emit("resetForm");
        $("#add_donor").modal("hide");
        $("#sub-project-modal").modal("hide");
    });

    Livewire.on("close-modal-other-direct-expense", () => {
        $("#add_other_direct_expenses").modal("hide");
        $("#edit_other_direct_expenses").modal("hide");
    });

    //on load select2
    if ($("#project_type").attr("wire:model") == "project_type") {
        Livewire.emit(
            "updateProjectType",
            $("#project_type").val(),
            "project_type",
            $("#project_type").val()
        );
    }

    if ($("#currency").attr("wire:model") == "currency") {
        Livewire.emit(
            "updateCurrency",
            $("#currency").val(),
            "project_type",
            $("#currency").val()
        );
    }

    Livewire.on("loadCurrencyFormatter", () => {
        initializeAutoNumeric();
    });
});

let digitValidate = function (eleId) {
    let ele = document.getElementById(eleId);

    ele.value = ele.value.replace(/[^0-9]/g, "");
};

//varication code
let tabChange = function (event, val) {
    let currentInput = document.getElementById("input" + val);
    let previousInput = document.getElementById("input" + (val - 1));
    let nextInput = document.getElementById("input" + (val + 1));

    if (event.key === "Backspace" && previousInput) {
        previousInput.focus();
    } else if (event.key !== "Backspace" && nextInput) {
        nextInput.focus();
    }
};

//common swal modal
function statusModal(val) {
    // const loader = document.createElement('div');
    // loader.id = 'loader';
    // document.body.appendChild(loader);

    Swal.fire({
        toast: true,
        title: val.title,
        text: val.text,
        animation: false,
        position: "bottom",
        showConfirmButton: false,
        timer: 2000,
        position: "top-right",
        timerProgressBar: true,
        customClass: {
            popup: val.status == "success" ? "success" : "error",
        },
        html:
            `
          <div class="custom-toast">
                  <span class="custom-toast-close"><img src="../images/icons/alert-cross-icon.svg" alt="cross"></span>
                  <div class="custom-toast-content">

              <p>` +
            val.text +
            `</p>
            </div>
          </div>

          `,
        onAfterClose: function () {
            window.location.href = window.location.href;
        },
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });
}

document.addEventListener("click", function (event) {
    var modal = document.getElementById("add_donor");
    // Check if the click target is outside the modal
    if (event.target == modal) {
        // Reload the page
        $(".js-example-basic-single").select2();
    }
});

$(document).on("keydown", function (e) {
    if (e.keyCode === 13) {
        // $('.save-table-btn').trigger('click')
        // $('.correct-row').trigger('click')
    }
});

function updateIndirectExpensesUnitsViaEnter(e) {
    if (e.keyCode == 13) {
        var input = e.target;
        var value = input.value.trim() === "" ? 0 : input.value;
        var employeeId = input.dataset.id;
        var categoryId = e.target.dataset.category;
        var rate = e.target.dataset.rate;
        var otherExpense = e.target.dataset.expense;
        if (otherExpense !== undefined) {
            rate = $(".OdUnitCost" + otherExpense + categoryId).val();
            Livewire.emit(
                "updateOtherIndirectExpenses",
                "units",
                value,
                otherExpense,
                categoryId,
                rate
            );
        } else {
            Livewire.emit(
                "updateIndirectExpenses",
                "units",
                value,
                employeeId,
                categoryId,
                rate
            );
        }

        e.target.blur();
    }
}

function updateIndirectExpenseCurrentYearCost(e) {
    if (e.keyCode == 13) {
        var input = e.target;
        var value = input.value.trim() === "" ? 0 : input.value;
        var employeeId = input.dataset.id;
        var categoryId = e.target.dataset.category;
        var rate = e.target.dataset.rate;
        var otherExpense = e.target.dataset.expense;
        if (otherExpense !== undefined) {
            rate = $(".OdUnitCost" + otherExpense + categoryId).val();
            Livewire.emit(
                "updateOtherIndirectExpenses",
                "actual_cost_till_date",
                value,
                otherExpense,
                categoryId,
                rate
            );
        } else {
            Livewire.emit(
                "updateIndirectExpenses",
                "actual_cost_till_date",
                value,
                employeeId,
                categoryId,
                rate
            );
        }
        e.target.blur();
    }
}

function updateIndirectExpenseUnitCost(e) {
    if (e.keyCode == 13) {
        var input = e.target;
        var value = input.value.trim() === "" ? 0 : input.value;
        var employeeId = input.dataset.id;
        var categoryId = e.target.dataset.category;
        var rate = e.target.dataset.rate;
        var otherExpense = e.target.dataset.expense;
        if (otherExpense !== undefined) {
            rate = $(".OdUnitCost" + otherExpense + categoryId).val();
            Livewire.emit(
                "updateOtherIndirectExpenses",
                "cost_per_unit",
                value,
                otherExpense,
                categoryId,
                rate
            );
        } else {
            Livewire.emit(
                "updateIndirectExpenses",
                "cost_per_unit",
                value,
                employeeId,
                categoryId,
                rate
            );
        }
        e.target.blur();
    }
}

$(document).on("click", ".correct-row", function () {
    let button = $(this);
    let error = 0,
        erroHtml;
    let clickEmployeeRow = $(this).data("click-employee-row");
    let year = $(this).data("year");

    let employee = $(".rows_" + clickEmployeeRow + "_employee").val();

    let approva_budget = $(
        ".rows_" + clickEmployeeRow + "_approval_budget"
    ).text();

    let type_id = $(this).attr("data-correct-row");

    let type = null;

    if (type_id == 1) {
        type = "employee";
    } else if (type_id == 2) {
        type = "consultant";
    } else if (type_id == 3) {
        type = "sub-grantee";
    } else if (type_id == 4) {
        type = "travel";
    } else if (type_id == 5) {
        type = "meeting";
    } else if (type_id == 6) {
        type = "other direct cost";
    }

    if (employee == "") {
        erroHtml = "Please fill the " + type;

        $(".rows_" + clickEmployeeRow + "_employee_error")
            .html(erroHtml)
            .attr("style", "display:flex !important");

        error++;
    } else {
        $(".rows_" + clickEmployeeRow + "_employee_error").attr(
            "style",
            "display:none !important"
        );
    }

    let note = $(".rows_" + clickEmployeeRow + "_note").val();

    let unit = $(".rows_" + clickEmployeeRow + "_unit").val();

    if (type_id != 5) {
        if (unit == "") {
            erroHtml = "Please enter unit";

            $(".rows_" + clickEmployeeRow + "_project_unit_error")
                .html(erroHtml)
                .attr("style", "display:flex !important");

            error++;
        } else {
            $(".rows_" + clickEmployeeRow + "_project_unit_error").attr(
                "style",
                "display:none !important"
            );
        }
    } else {
        unit = $(".rows_" + clickEmployeeRow + "_unit").val()
            ? $(".rows_" + clickEmployeeRow + "_unit").val()
            : 0;
    }

    let expenses = $(".rows_" + clickEmployeeRow + "_expenses").val();

    if (expenses == "") {
        expenses = 0;
    }

    if (type_id != 6 && type_id != 5) {
        if (expenses == "") {
            // erroHtml = "Please enter expenses";
            // $(".rows_" + clickEmployeeRow + "_project_expenses_error")
            //     .html(erroHtml)
            //     .attr("style", "display:flex !important");
            // error++;
        } else {
            $(".rows_" + clickEmployeeRow + "_project_expenses_error").attr(
                "style",
                "display:none !important"
            );
        }
    }

    if (expenses != "") {
        if (
            parseInt(dutchCurrencyToNumber(approva_budget)) <=
            parseInt(dutchCurrencyToNumber(expenses))
        ) {
            // erroHtml = 'Actual Expenses should be less than Approval Budget'
            // $('.rows_' + clickEmployeeRow + '_project_expenses_error')
            //   .html(erroHtml)
            //   .attr('style', 'display:flex !important')
            // error++
        } else {
            $(".rows_" + clickEmployeeRow + "_project_expenses_error").attr(
                "style",
                "display:none !important"
            );
        }
    }

    $(this)
        .parentsUntil("tr")
        .parent()
        .find(".text-error-500:visible:first")
        .parent()
        .find("select,input")
        .focus();

    let sub_project_tab = $(".sub-project-result")
        .children(".active")
        .attr("id");

    $("#" + sub_project_tab).addClass("active");

    let remaining_balanace = $(
        ".rows_" + clickEmployeeRow + "_remaining_balance"
    ).text();

    let total_approval_budget = $(
        ".rows_" + clickEmployeeRow + "_approval_budget"
    ).text();

    let table = $(this).closest("table");
    let title = table.find("thead th:first").text();

    let unit_costs = $(".rows_" + clickEmployeeRow + "_unitcost").text();

    if (type_id != 5) {
        if (unit_costs == "") {
            unit_costs = $(".rows_" + clickEmployeeRow + "_unitcost").val();

            if (unit_costs == "") {
                erroHtml = "Please enter unit cost";

                $(".rows_" + clickEmployeeRow + "_unit_cost_error")
                    .html(erroHtml)
                    .attr("style", "display:flex !important");

                error++;
            }
        }
    } else {
        unit_costs = $(".rows_" + clickEmployeeRow + "_unitcost").val()
            ? $(".rows_" + clickEmployeeRow + "_unitcost").val()
            : 0;
    }

    let sub_project_id = $(this).closest("table").attr("data-sub-project");

    let indirect_cost_percentage = null;

    if (sub_project_id == undefined) {
        indirect_cost_percentage = $(
            '.indirect_cost[data-indirect-percentage-year="' + year + '"]'
        ).val();
    } else {
        indirect_cost_percentage = $(
            ".indirect_cost[data-indirect-percentage-year='" +
                year +
                sub_project_id +
                "']"
        ).val();
    }

    var resultData = {
        sub_project_id: sub_project_id,

        row_id: clickEmployeeRow,

        employee: employee,

        note: note,

        unit: unit,

        unit_costs: unit_costs,

        expenses: expenses,

        remaining_balanace: remaining_balanace,

        title: title,

        total_approval_budget: total_approval_budget,

        last_tab: sub_project_tab,
        year: year,

        indirect_cost_percentage: indirect_cost_percentage
            ? indirect_cost_percentage
            : 0,
    };

    if (error === 0) {
        showLoader(button);

        Livewire.emit("saveProjectdata", resultData);

        Livewire.on("projectCreateSaved", (response) => {
            $(this).closest("tr").remove();
            removeLoader(button);
            disabledSelect2options();
        });

        //statusModal({title:"Success!",text:"Data Added Successfully!",status:"success"});

        //window.location.href = window.location.href;
    }
});

$(document).on("select2:open", ".employee_data", function (e) {
    let select2id = $(this).attr("id");
});

$(document).on("click", ".add_employee_row", function (e) {
    let select2id = $(this).attr("data-type-id");
    let sub_project_id = $(this).attr("data-sub-project-id");

    let collapse_id = $(this).attr("data-id");

    Livewire.emit("updateSelect2", select2id, sub_project_id, collapse_id);
});

// $(document).on("change", ".employee_data", function () {
//     let rowId = $(this).attr("data-id");
//     let type = $(this).attr("data-type");
//     let year = $(this).attr("data-year");
//     let value = $(this).val();
//     if (type != 6) {
//         $.ajax({
//             url: "/get-current-project-user-data",
//             method: "GET",
//             data: { id: $(this).val(), year: year },
//             success: function (response) {
//                 console.log(response);
//                 $(".rows_" + rowId + "_unitcost").text(
//                     netherlandformatCurrency(response)
//                 );
//             },
//             error: function (xhr, status, error) {
//                 console.error(error);
//             },
//         });
//     }
// });

$(document).on("change", ".employee_data", function () {
    let rowId = $(this).attr("data-id");
    let type = $(this).attr("data-user-type-id");
    let year = $(this).attr("data-year");
    let value = $(this).val();
    let recordId = $(this).attr("data-record-id");

    if (type != 6) {
        // First AJAX request
        $.ajax({
            url: "/get-current-project-user-data",
            method: "GET",
            data: { id: value, year: year, record: recordId },
            success: function (response) {
                if (!response){
                    return;
                }
                $(".rows_" + rowId + "_unitcost").text(
                        netherlandformatCurrency(response)
                    );
                // Delay the second part to ensure the first AJAX request has time to complete
                setTimeout(function () {
                    let dutchexpenses;
                    let currentval = $(".rows_" + rowId + "_unit").val();
                    let unitCost = $(".rows_" + rowId + "_unitcost").text();

                    if (unitCost == "") {
                        unitCost = $(".rows_" + rowId + "_unitcost").val();
                    }

                    let expenses = $(".rows_" + rowId + "_expenses").val()
                        ? $(".rows_" + rowId + "_expenses").val()
                        : 0;
                    let caluculation =
                        dutchCurrencyToNumber(currentval) *
                        dutchCurrencyToNumber(unitCost);
                    let dutchcaluculation = caluculation;

                    if (expenses != 0) {
                        dutchexpenses = dutchCurrencyToNumber(expenses);
                    } else {
                        dutchexpenses = 0;
                    }

                    let remaining_caluculation =
                        dutchcaluculation - dutchexpenses;

                    $(".rows_" + rowId + "_approval_budget").text(
                        netherlandformatCurrency(dutchcaluculation)
                    );

                    $(".rows_" + rowId + "_remaining_balance").text(
                        netherlandformatCurrency(remaining_caluculation)
                    );

                    if (remaining_caluculation < 0) {
                        $(".rows_" + rowId + "_remaining_balance")
                            .parent()
                            .addClass("text-error-500");
                    } else {
                        $(".rows_" + rowId + "_remaining_balance")
                            .parent()
                            .removeClass("text-error-500");
                    }
                }, 500);
            },
            error: function (xhr, status, error) {
                console.error(error);
            },
        });
    }
    // Save button click logic
    let saveButton = $(this).siblings(".save-pill");
    setTimeout(function () {
        if (saveButton) {
            saveButton.click();
            $(this).blur();
        }
    }, 2000);
});

$(document).on("keyup change", ".project_unit", function (e) {
    let dutchexpenses;

    let rowId = $(this).attr("data-id");

    let currentval = $(this).val();
    console.log("currentval", currentval);

    let unitCost = $(".rows_" + rowId + "_unitcost").text();
    console.log("unitCost", unitCost);

    if (unitCost == "") {
        unitCost = $(".rows_" + rowId + "_unitcost").val();
    }
    console.log("unitCost", unitCost);
    let expenses = $(".rows_" + rowId + "_expenses").val()
        ? $(".rows_" + rowId + "_expenses").val()
        : 0;
    console.log("expenses", expenses);

    let calculation =
        dutchCurrencyToNumber(currentval) * dutchCurrencyToNumber(unitCost);
    console.log("calculation", calculation);
    let dutchCalculation = calculation;

    if (expenses != 0) {
        dutchexpenses = dutchCurrencyToNumber(expenses);
    } else {
        dutchexpenses = 0;
    }

    let remaining_caluculation = dutchCalculation - dutchexpenses;
    console.log("remaining_caluculation", remaining_caluculation);
    $(".rows_" + rowId + "_approval_budget").text(
        netherlandformatCurrency(dutchCalculation)
    );

    $(".rows_" + rowId + "_remaining_balance").text(
        netherlandformatCurrency(remaining_caluculation)
    );

    if (e.keyCode === 13) {
        let saveButton = $(this).siblings(".save-pill");

        if (saveButton) {
            saveButton.click();
            $(this).blur();
        }
    }
});

// $(document).on("change", ".employee_data", function () {
//     let rowId = $(this).attr("data-id");
//     let type = $(this).attr("data-type");
//     if (type != 6) {
//         setTimeout(function () {
//             let dutchexpenses;

//             let currentval = $(".rows_" + rowId + "_unit").val();

//             let unitCost = $(".rows_" + rowId + "_unitcost").text();

//             if (unitCost == "") {
//                 unitCost = $(".rows_" + rowId + "_unitcost").val();
//             }

//             let expenses = $(".rows_" + rowId + "_expenses").val()
//                 ? $(".rows_" + rowId + "_expenses").val()
//                 : 0;
//             let caluculation =
//                 dutchCurrencyToNumber(currentval) *
//                 dutchCurrencyToNumber(unitCost);

//             let dutchcaluculation = caluculation;

//             if (expenses != 0) {
//                 dutchexpenses = dutchCurrencyToNumber(expenses);
//             } else {
//                 dutchexpenses = 0;
//             }

//             let remaining_caluculation = dutchcaluculation - dutchexpenses;

//             $(".rows_" + rowId + "_approval_budget").text(
//                 netherlandformatCurrency(dutchcaluculation)
//             );

//             $(".rows_" + rowId + "_remaining_balance").text(
//                 netherlandformatCurrency(remaining_caluculation)
//             );
//             if (remaining_caluculation < 0) {
//                 $(".rows_" + rowId + "_remaining_balance")
//                     .parent()
//                     .addClass("text-error-500");
//             } else {
//                 $(".rows_" + rowId + "_remaining_balance")
//                     .parent()
//                     .removeClass("text-error-500");
//             }
//         }, 1000);
//     }
//     let saveButton = $(this).siblings(".save-pill");
//     setTimeout(function () {
//         if (saveButton) {
//             saveButton.click();
//             $(this).blur();
//         }
//     }, 2000);
// });

function dutchCurrencyToNumber(currencyString) {
    // Remove the thousand separators (periods)
    let numberString = currencyString.replace(/\./g, "");
    // Replace the decimal separator (comma) with a period
    numberString = numberString.replace(/,/g, ".");
    return numberString;
}

$(document).on("keyup", ".project_expenses", function (e) {
    let rowId = $(this).attr("data-id");

    let currentval = $(this).val();

    let _approval_budget = $(".rows_" + rowId + "_approval_budget").text();

    let caluculation =
        dutchCurrencyToNumber(_approval_budget) -
        dutchCurrencyToNumber(currentval);

    let dutchcaluculation = caluculation;

    caluculation <= 0 ? 0 : caluculation;

    $(".rows_" + rowId + "_remaining_balance").text(
        netherlandformatCurrency(dutchcaluculation)
    );
    if (dutchcaluculation < 0) {
        $(".rows_" + rowId + "_remaining_balance")
            .parent()
            .addClass("text-error-500");
    } else {
        $(".rows_" + rowId + "_remaining_balance")
            .parent()
            .removeClass("text-error-500");
    }

    if (e.keyCode === 13) {
        let saveButton = $(this).siblings(".save-pill");

        if (saveButton) {
            saveButton.click();
            $(this).blur();
        }
    }
});

$(document).on("keyup", ".unit_cost", function (e) {
    let rowId = $(this).attr("data-id");

    let currentval = $(this).val();

    let unit = $(".rows_" + rowId + "_unit").val();

    let caluculation =
        dutchCurrencyToNumber(unit) * dutchCurrencyToNumber(currentval);

    let expenses = $(".rows_" + rowId + "_expenses").val();

    let dutchcaluculation = caluculation;

    caluculation <= 0 ? 0 : caluculation;

    let dutchremainingcaluculation =
        caluculation - dutchCurrencyToNumber(expenses);

    $(".rows_" + rowId + "_approval_budget").text(
        netherlandformatCurrency(dutchcaluculation)
    );

    $(".rows_" + rowId + "_remaining_balance").text(
        netherlandformatCurrency(dutchremainingcaluculation)
    );
    if (dutchremainingcaluculation < 0) {
        $(".rows_" + rowId + "_remaining_balance")
            .parent()
            .addClass("text-error-500");
    } else {
        $(".rows_" + rowId + "_remaining_balance")
            .parent()
            .removeClass("text-error-500");
    }

    if (e.keyCode === 13) {
        let saveButton = $(this).siblings(".save-pill");

        if (saveButton) {
            saveButton.click();
            $(this).blur();
        }
    }
});

$(document).on("click", ".delete_project_row", function () {
    let findtable = $(this).closest("table").attr("id");

    let attr = $(this).attr("data-remove");

    let detailsrow = $("#" + findtable)
        .find("tbody")
        .find(".append-row").length;

    if (detailsrow) {
        $("#" + findtable)
            .find(".add_employee_row")
            .prop("disabled", false);
    }

    if ($(".append-row").length >= 1) {
        $("." + findtable).attr("style", "display: table-cell !important");
    }

    $(this).closest("tr").remove();
});

$(document).on("keyup click", ".indirect_cost", function (e) {
    let currentvalue = $(this).val();
    let percentage = 100;

    let id = $(this).attr("data-id");

    let total_budget = dutchCurrencyToNumber($(".total_approval_budget_" + id)
        .text());
    console.log("total_budget", total_budget);

    let total_actual_expenses =dutchCurrencyToNumber($(".total_actual_expenses_" + id).text());

    console.log("total_actual_expenses", total_actual_expenses);

    let total_remaining_balance = dutchCurrencyToNumber($(
        ".total_remaining_balance_" + id
    ).text());
    console.log("total_remaining_balance", total_remaining_balance);

    let indirect_cost_approval = (total_budget * currentvalue) / percentage;
    console.log("indirect_cost_approval", indirect_cost_approval);

    let actual_expenses = (total_actual_expenses * currentvalue) / percentage;
    console.log("actual_expenses", actual_expenses);

    let remaining_balance =
        (total_remaining_balance * currentvalue) / percentage;
    console.log("remaining_balance", remaining_balance);

    let sub_project_id = $(this).attr("data-sub-project");

    let project_id = $(this).attr("data-project-id");

    $(".total_approval_budget_indirect_cost_" + id).text(
        netherlandformatCurrency(indirect_cost_approval.toFixed(2))
    );

    let indiect_cost_appoval_round =
        indirect_cost_approval + parseInt(total_budget);

    $(".total_estimate_approval_budget_" + id).text(
        netherlandformatCurrency(indiect_cost_appoval_round.toFixed(2))
    );

    $(".total_actual_expenses_indirect_cost_" + id).text(
        netherlandformatCurrency(actual_expenses.toFixed(2))
    );

    let estimate_actual_expense =
        actual_expenses + parseInt(total_actual_expenses);

    $(".total_estimate_actual_expenses_" + id).text(
        netherlandformatCurrency(estimate_actual_expense.toFixed(2))
    );

    $(".total_remaining_balance_indirect_cost_" + id).text(
        netherlandformatCurrency(remaining_balance.toFixed(2))
    );
    if (remaining_balance.toFixed(2) < 0) {
        $(".total_remaining_balance_indirect_cost_" + id)
            .parent()
            .addClass("text-error-500");
    } else {
        $(".total_remaining_balance_indirect_cost_" + id)
            .parent()
            .removeClass("text-error-500");
    }

    let remaining_balance_round =
        remaining_balance + parseInt(total_remaining_balance);

    $(".total_estimate_remaining_balance_" + id).text(
        netherlandformatCurrency(remaining_balance_round.toFixed(2))
    );

    if (remaining_balance_round < 0) {
        $(".total_estimate_remaining_balance_" + id)
            .parent()
            .addClass("text-error-500");
    } else {
        $(".total_estimate_remaining_balance_" + id)
            .parent()
            .removeClass("text-error-500");
    }

    let resultData = {
        approval_budget: indirect_cost_approval + parseInt(total_budget),

        actual_expenses: actual_expenses + parseInt(total_actual_expenses),

        remaining_balance:
            remaining_balance + parseInt(total_remaining_balance),

        sub_project_id: sub_project_id,

        project_id: project_id,
    };

    if (e.keyCode === 13) {
        let saveButton = $(this).siblings(".save-pill-indirect-cost");

        if (saveButton) {
            saveButton.click();
            $(this).blur();
        }
    }
});

$(document).on("click", ".international", function () {
    Livewire.emit("updateUserType", $("#user_type").val());

    internationalCountry(".phone_modal2");

    $(".phone_modal2").trigger("countrychange");
});

$(document).on("click", ".line_item_country", function () {
    Livewire.emit("updateUserType", $(".user_type_line_item").val());

    internationalCountry(".phone_modal3");
});

$(document).on("click", ".line_item_country1", function () {
    Livewire.emit("updateUserType", $(".user_type_line_item1").val());

    internationalCountry(".phone_modal3");
});

$(document).on("click", ".line_item_country2", function () {
    Livewire.emit("updateUserType", $(".user_type_line_item2").val());

    internationalCountry(".phone_modal3");
});

$(document).ready(function () {
    try {
        setTimeout(() => {
            $(document).find(".edit-donot-phone").trigger("change");

            $(".phone_modal4").trigger("countrychange");

            internationalCountry(".donor_create_phone");

            $(".donor_create_phone").trigger("countrychange");

            Livewire.emit("updatedProjectDonorId", $("#donor_id_edit").val());

            Livewire.emit(
                "updatedProjectDonorId",
                $("#consultant_id_edit").val()
            );
        }, 1000);
    } catch (e) {
        console.log(e);
    }

    internationalCountry(".phone_modal");
    internationalCountry(".phone_modal4");

    $(document).on("click", ".change-sub-project-tab", function () {
        let last_tab = $(this).attr("id");
        let resultData = {
            last_tab: last_tab,
            project_id: $(".on-load-project-id").attr("data-project-id"),
        };
        $.ajax({
            url: "/update-last-tab",
            method: "GET",
            data: { data: resultData },
            dataType: "json",
            success: function (response) {},
            error: function (xhr, status, error) {},
        });
    });
});

$(document).on("change", ".create-donot-phone", function () {
    Livewire.emit("updatedProjectDonorId", $("#project_create_user").val());
    let thisKey = $(this);
    Livewire.on("countryeditSelected", function (country) {
        internationalCountry(".phone_modal");
        internationalCountry(".donor_create_phone");
        thisKey.intlTelInput("setCountry", country.toLowerCase());
    });
    $(".phone_modal").trigger("countrychange");
    $(".donor_create_phone").trigger("countrychange");
});

$(document).on("change", ".edit-donot-phone", function () {
    Livewire.emit("updatedProjectDonorId", $("#project_create_user").val());

    let thisKey = $(this);

    Livewire.on("countryeditSelected", function (country) {
        internationalCountry(".phone_modal4");

        thisKey.intlTelInput("setCountry", country.toLowerCase());
    });
});

function internationalCountry(value) {
    $(value).intlTelInput({
        geoIpLookup: function (callback) {
            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (
                resp
            ) {
                var countryCode = resp && resp.country ? resp.country : "";
                callback(countryCode);
            });
        },
        //hiddenInput: "full_number",
        initialCountry: "auto",
        separateDialCode: true,
        //autoPlaceholder: "off",
        nationalMode: false,
        preferredCountries: ["us", "in", "nl"],
    });

    $(value).on("countrychange", function (e) {
        // $(this).val('');

        var selectedCountry = $(this).intlTelInput("getSelectedCountryData");
        var dialCode = selectedCountry.dialCode;
        var maskNumber = intlTelInputUtils.getExampleNumber(
            selectedCountry.iso2,
            0,
            0
        );

        maskNumber = maskNumber.replace("+" + dialCode + " ", "");

        mask = maskNumber.replace(/[0-9+]/gi, "0");

        // if (selectedCountry.iso2 == undefined)
        // {
        //   let thisKey2 = $(this);
        //   thisKey2.intlTelInput('setCountry', 'nl');
        // }

        let thisKey = $(this);

        if (value == ".phone_modal") {
            Livewire.on("countrySelected", function (country) {
                thisKey.intlTelInput("setCountry", country.toLowerCase());
            });

            Livewire.on("countrycreatedonornumber", function (phone) {
                $(value).val(phone);
            });

            if (selectedCountry.iso2 == undefined) {
                Livewire.emit("updateCountry", "nl", "country", "nl");
            } else {
                Livewire.emit("updateCountry", selectedCountry.iso2);
            }
        } else if (value == ".phone_modal4") {
            Livewire.on("countryeditSelected", function (country) {
                thisKey.intlTelInput("setCountry", country.toLowerCase());
            });

            Livewire.on("countryeditdonornumber", function (phone) {
                $(value).val(phone);
            });
            if (selectedCountry.iso2 == undefined) {
                Livewire.emit("updateCountry", "nl", "country", "nl");
            } else {
                Livewire.emit("updateCountry", selectedCountry.iso2);
            }
        } else if (value == ".phone_modal2") {
            if (selectedCountry.iso2 == undefined) {
                Livewire.emit("updateCountry", "nl", "country", "nl");
            } else {
                Livewire.emit(
                    "updateCountry",
                    selectedCountry.iso2,
                    "country",
                    selectedCountry.iso2
                );
            }
        } else if (value == ".phone_modal3") {
            if (selectedCountry.iso2 == undefined) {
                Livewire.emit("updateCountry", "nl", "country", "nl");
            } else {
                Livewire.emit(
                    "updateCountry",
                    selectedCountry.iso2,
                    "country",
                    selectedCountry.iso2
                );
            }
        } else if (value == ".donor_create_phone") {
            Livewire.on("countryeditSelected", function (country) {
                thisKey.intlTelInput("setCountry", country.toLowerCase());
            });

            if (selectedCountry.iso2 == undefined) {
                Livewire.emit("updateCountry", "nl", "country", "nl");
            } else {
                Livewire.emit(
                    "updateCountry",
                    selectedCountry.iso2,
                    "country",
                    selectedCountry.iso2
                );
            }

            Livewire.on("countrySelected", function (country) {
                thisKey.intlTelInput("setCountry", country.toLowerCase());
            });
        }

        // $(value).mask(mask,{ placeholder: maskNumber });

        $(this).attr("placeholder", maskNumber);
    });
}

//tooltip initialize
$(document).on("mouseenter", ".toolkitdata", function () {
    $(this).tooltip();
});

function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

$(document).on("click", ".units-input-number", function () {
    let is_class = $(this).find(".table-input").prop("disabled", false);

    if (is_class.length <= 0) {
        $(this).find(".table-select").prop("disabled", false);
    }

    $(this).find(".save-pill").attr("style", "display: block !important");
    $(this)
        .find(".save-pill-revision")
        .attr("style", "display: block !important");
    $(this).find(".save-table-btn").attr("style", "display: block !important");

    $(this)
        .find(".save-pill-indirect-cost")
        .attr("style", "display: block !important");
});

document.addEventListener("livewire:load", function () {
    Livewire.on("closeSubprojectmodal", function (data) {
        $("#sub-project-modal").modal("hide");
    });

    Livewire.on("deleteProjectmodal", function (data) {
        $("#delete_project").modal("hide");
    });

    Livewire.on("deleteItemProject", function (data) {
        $("#delete_item_project").modal("hide");
        $("#delete_item_all_project").modal("hide");
    });

    Livewire.on("close-modal", function (data) {
        $("#add_donor").modal("hide");
    });

    Livewire.on("close-modal-lineitem", function (data) {
        $("#add_user_project").modal("hide");

        $("#add_consultant_project").modal("hide");

        $("#add_sub_grantee_project").modal("hide");
    });

    Livewire.on("close-modal-role", function (data) {
        $("#add_role").modal("hide");
    });

    Livewire.on("commonModal", function (data) {
        $("#" + data).modal("hide");
    });
});

$(document).on("click", ".save-pill", function () {
    let error = 0,
        erroHtml;
    
    //   let collapse = $(this).attr('data-id')

    // $('#' + collapse).toggleClass('collapsed')

    let clickEmployeeRow = $(this).attr("data-id");

    let year = $(this).attr("data-year");

    let employee = $(".rows_" + clickEmployeeRow + "_employee").val();

    if (employee == "" || employee == null) {
        employee = $(".rows_" + clickEmployeeRow + "_employee_input").val();
    }

    let approva_budget = $(
        ".rows_" + clickEmployeeRow + "_approval_budget"
    ).text();

    let type = null;

    if (clickEmployeeRow == 1) {
        type = "employee";
    } else if (clickEmployeeRow == 2) {
        type = "consultant";
    } else if (clickEmployeeRow == 3) {
        type = "sub-grantee";
    } else if (clickEmployeeRow == 4) {
        type = "travel";
    } else if (clickEmployeeRow == 5) {
        type = "meeting";
    } else if (clickEmployeeRow == 6) {
        type = "other direct cost";
    }

    if (employee == "") {
        erroHtml = "Please select the " + type;

        $(".rows_" + clickEmployeeRow + "_employee_error")
            .html(erroHtml)
            .attr("style", "display:flex !important");

        error++;
    } else {
        $(".rows_" + clickEmployeeRow + "_employee_error").attr(
            "style",
            "display:none !important"
        );
    }

    let note = $(".rows_" + clickEmployeeRow + "_note").val();

    let unit = $(".rows_" + clickEmployeeRow + "_unit").val();

    if (unit == "") {
        erroHtml = "Please enter unit";

        $(".rows_" + clickEmployeeRow + "_project_unit_error")
            .html(erroHtml)
            .attr("style", "display:flex !important");

        error++;
    } else {
        $(".rows_" + clickEmployeeRow + "_project_unit_error").attr(
            "display:none !important"
        );
    }

    let expenses = $(".rows_" + clickEmployeeRow + "_expenses").val();

    if (expenses == "") {
        // erroHtml = "Please enter expenses";
        // $(".rows_" + clickEmployeeRow + "_project_expenses_error")
        //     .html(erroHtml)
        //     .attr("style", "display:flex !important");
        // error++;
    } else {
        $(".rows_" + clickEmployeeRow + "_project_expenses_error").attr(
            "display:none !important"
        );
    }

    if (expenses != "") {
        if (
            parseInt(dutchCurrencyToNumber(approva_budget)) <=
            parseInt(dutchCurrencyToNumber(expenses))
        ) {
            // erroHtml = 'Actual Expenses should be less than Approval Budget'
            // $('.rows_' + clickEmployeeRow + '_project_expenses_error')
            //   .html(erroHtml)
            //   .attr('style', 'display:flex !important')
            // error++
        } else {
            $(".rows_" + clickEmployeeRow + "_project_expenses_error").attr(
                "style",
                "display:none !important"
            );
        }
    }

    let sub_project_tab = $(".sub-project-result")
        .children(".active")
        .attr("id");

    $("#" + sub_project_tab).addClass("active");

    let total_approval_budget = $(
        ".rows_" + clickEmployeeRow + "_approval_budget"
    ).text();

    let table = $(this).closest("table");

    let title = table.find("thead th:first").text();

    let unit_costs = $(".rows_" + clickEmployeeRow + "_unitcost").text();

    if (unit_costs == "") {
        unit_costs = $(".rows_" + clickEmployeeRow + "_unitcost").val();
    }

    let sub_project_id = $(this).closest("table").attr("data-sub-project");

    let sub_project_data_id = $(this).attr("data-sub-project-data-id");

    let indirect_cost_percentage = null;
    if (sub_project_id == undefined) {
        indirect_cost_percentage = $(
            '.indirect_cost[data-sub-project="0"][data-indirect-percentage-year=' +
                year +
                "]"
        ).val();
    } else {
        indirect_cost_percentage = $(
            ".indirect_cost[data-sub-project='" +
                sub_project_id +
                "'][data-indirect-percentage-year=" +
                year +
                sub_project_id +
                "]"
        ).val();
    }

    let remaining_balanace =
        dutchCurrencyToNumber(total_approval_budget) -
        dutchCurrencyToNumber(expenses);
    var resultData = {
        sub_project_id: sub_project_id,

        row_id: clickEmployeeRow,

        employee: employee,

        note: note,

        unit: unit,

        unit_costs: unit_costs,

        expenses: expenses,

        remaining_balanace: remaining_balanace,

        title: title,

        total_approval_budget: total_approval_budget,

        sub_project_data_id: sub_project_data_id,

        last_tab: sub_project_tab,

        year: year,

        indirect_cost_percentage: indirect_cost_percentage
            ? indirect_cost_percentage
            : 0,
    };
    console.log("resultData", resultData);

    if (error === 0) {
        Livewire.emit("updateProjectdata", resultData);

        //statusModal({title:"Success!",text:"Data updated successfully!",status:"success"});

        //window.location.href = window.location.href;
    }
});

$(document).on("change", ".change_employee", function () {
    let rowId = $(this).attr("data-id");

    setTimeout(function () {
        let currentval = $(".rows_" + rowId + "_unit").val();

        let unitCost = $(".rows_" + rowId + "_unitcost").text();

        let caluculation =
            dutchCurrencyToNumber(currentval) * dutchCurrencyToNumber(unitCost);

        let _approval_budget = $(".rows_" + rowId + "_approval_budget").text(
            netherlandformatCurrency(caluculation)
        );

        let get_approval_budget = $(
            ".rows_" + rowId + "_approval_budget"
        ).text();

        let project_expenses = $(".rows_" + rowId + "_expenses").val();

        let remaining_caluculation =
            dutchCurrencyToNumber(get_approval_budget) -
            dutchCurrencyToNumber(project_expenses);

        remaining_caluculation <= 0 ? 0 : remaining_caluculation;

        $(".rows_" + rowId + "_remaining_balance").text(
            netherlandformatCurrency(remaining_caluculation)
        );
    }, 2000);
});

$(document).on("click", ".save-pill-indirect-cost", function () {
    let currentvalue = $(this).parent().find(".indirect_cost").val();

    let percentage = 100;

    let id = $(this).attr("data-id");
    let year = $(this).attr("data-year");

    let total_budget = dutchCurrencyToNumber($(
        ".total_approval_budget_" + id
    ).text());

    let total_actual_expenses =dutchCurrencyToNumber($(".total_actual_expenses_" + id)
        .text())
        

    let total_remaining_balance =dutchCurrencyToNumber($(".total_remaining_balance_" + id)
        .text())
        
    
    let indirect_cost_approval = (total_budget * currentvalue) / percentage;


    let actual_expenses = (total_actual_expenses * currentvalue) / percentage;


    let remaining_balance =
        (total_remaining_balance * currentvalue) / percentage;

    let sub_project_id = $(this).attr("data-sub-project");

    let project_id = $(this).attr("data-project-id");

    let resultData = {
        approval_budget: indirect_cost_approval + parseInt(total_budget),

        actual_expenses: actual_expenses + parseInt(total_actual_expenses),

        remaining_balance:
            remaining_balance + parseInt(total_remaining_balance),

        sub_project_id: sub_project_id,

        project_id: project_id,

        percentage: currentvalue,
        year: year,
        indirect_cost_approval: indirect_cost_approval,
        actual_expenses: actual_expenses
    };

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    Livewire.emit("updateEstimateBudget", resultData);

    $.ajax({
        url: "/get-current-project-estimate-budget",
        method: "GET",
        data: { data: resultData },
        dataType: "json",
        success: function (response) {},
        error: function (xhr, status, error) {
            console.log(error);
        },
    });

    //statusModal({title:"Success!",text:"Data updated successfully!",status:"success"});

    // location.reload();

    // setTimeout(function()
    // {

    //   window.location.href = window.location.href

    // },400)
});

function currency($value) {
    if ($value == "usd") {
        return "$";
    } else if ($value == "gbp") {
        return "£";
    } else if ($value == "eur") {
        return "€";
    } else {
        return "€";
    }
}

$(document).on("change", ".select_country_modal", function (e) {
    Livewire.emit(
        "updateCountryCode",
        e.target.value,
        "updateCountryCode",
        e.target.value
    );
});

$(document).on("change", ".donor_select2", function (e) {
    console.log(e.target.value);
    Livewire.emit(
        "updateCountryCode",
        e.target.value,
        "updateCountryCode",
        e.target.value
    );
});

$(document).on("keydown", "input[type='number']", function (event) {
    limitOnInput(event, $(this)); // Call the function and pass the current input element
});

// The rest of your existing code for the function limitDecimalPlaces(event) goes here
function validateFloatKeyPress(el, evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    var number = el.value.split(".");

    // Allow only digits, the dot, and specific key codes
    if (charCode != 46 && (charCode < 48 || charCode > 57)) {
        return false;
    }

    // Allow only one dot
    if (number.length > 1 && charCode == 46) {
        return false;
    }

    // Get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");

    // Check for conditions to restrict input
    if (number.length > 1 && charCode != 46) {
        // Disallow input after a decimal point with more than two digits
        if (caratPos > dotPos && number[1].length >= 2) {
            return false;
        }
    } else if (number.length === 1) {
        var inputValue = parseInt(number[0] + String.fromCharCode(charCode));
        // Disallow input that would result in a value greater than 99

        if (isNaN(inputValue)) {
            el.value = "0";
        }
        if (inputValue > 99 || (inputValue === 0 && number[0] !== "0")) {
            return false;
        }
    }

    return true;

    //thanks: http://javascript.nwbox.com/cursor_position/
    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate();
            r.moveEnd("character", o.value.length);
            if (r.text == "") return o.value.length;
            return o.value.lastIndexOf(r.text);
        } else return o.selectionStart;
    }
}

function limitOnInput(event, inputElement) {
    const currentValue = inputElement.val();

    if (
        event.keyCode === 8 ||
        event.keyCode === 46 ||
        (event.keyCode >= 37 && event.keyCode <= 40)
    ) {
        return;
    }

    if (currentValue.length > 7 || parseInt(currentValue) > 10000000) {
        event.preventDefault();
    }
}

// Attach the restrictToInteger function using event delegation
document.addEventListener("input", function (event) {
    const target = event.target;
    if (target.matches('input[type="number"]')) {
        restrictToInteger.call(target);
    }
});

// Function to restrict the input to integers only
function restrictToInteger() {
    this.value = this.value.replace(/[^\d]/g, "");
}

$(document).on("click", "#cancel-modal", function () {
    $(".js-example-basic-single").select2();
});

$(document).on("click", "#cancel-modal2", function () {
    $(".js-example-basic-single").select2();

    // $('.select-project-type-create').select2();
    // $(".create-donot-phone").select2();
    // $(".edit-donot-phone").select2();
});

document.addEventListener("livewire:load", function () {
    Livewire.on("refreshSelect2", function () {
        $("#project_create_user").select2({
            ajax: {
                url: "/get-donor-project", // Replace with your AJAX endpoint
                method: "GET",
                dataType: "json",
                processResults: function (response) {
                    var data = $.map(response, function (obj) {
                        obj.id = obj.id;
                        obj.text = obj.name; // Use 'text' property for display text
                        return obj;
                    });
                    return {
                        results: data,
                    };
                },
                error: function (xhr, status, error) {
                    console.log(error);
                },
            },
        });
    });

    // $(".datepicker2").on("keydown", function(event) {

    //   event.preventDefault();
    // });
});

$(document).on("click", ".close-donor-modal", function () {
    $(".select-project-type-create").select2();
    $(".create-donot-phone").select2();
    $(".edit-donot-phone").select2();
});

$(document).ready(function () {
    disabledSelect2options();

    // Update the disabled options whenever the user changes a selection
    $(".change_employee").change(function () {
        $(".tab-pane").each(function () {
            var $group = $(this);
            var selectedValues = [];

            $group.find(".change_employee").each(function () {
                var selectedValue = $(this).val();
                if (selectedValue) {
                    selectedValues.push(selectedValue);
                }
            });

            $group.find(".change_employee").each(function () {
                var selectedValue = $(this).val();
                $(this).find("option").prop("disabled", false);

                $(this)
                    .find("option")
                    .each(function () {
                        var optionValue = $(this).val();
                        if (
                            selectedValues.includes(optionValue) &&
                            optionValue !== selectedValue
                        ) {
                            $(this).prop("disabled", true);
                        }
                    });
            });
        });
    });
});

function disabledSelect2options() {
    $(".tab-pane").each(function () {
        var $group = $(this);
        var selectedValues = [];

        $group.find(".change_employee").each(function () {
            var selectedValue = $(this).val();
            if (selectedValue) {
                selectedValues.push(selectedValue);
            }
        });
        $group.find(".change_employee").each(function () {
            var selectedValue = $(this).val();
            $(this).find("option").prop("disabled", false);
            $(this)
                .find("option")
                .each(function () {
                    var optionValue = $(this).val();
                    if (
                        selectedValues.includes(optionValue) &&
                        optionValue !== selectedValue
                    ) {
                        $(this).prop("disabled", true);
                    }
                });
        });
    });
}

function validateexpnseKeyPress(el, evt) {
    var charCode = evt.which ? evt.which : event.keyCode;
    var number = el.value.split(".");

    // Allow only digits, the dot, and specific key codes
    if (charCode != 46 && (charCode < 48 || charCode > 57)) {
        return false;
    }

    // Allow only one dot
    if (number.length > 1 && charCode == 46) {
        return false;
    }

    // Get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");

    // Check for conditions to restrict input
    if (number.length > 1 && charCode != 46 && caratPos > dotPos) {
        // Disallow input after a decimal point with more than two digits
        var digitsAfterDot = number[1].length;
        if (digitsAfterDot >= 2) {
            return false;
        }
    }

    return true;
}

// thanks: http://javascript.nwbox.com/cursor_position/
function getSelectionStart(o) {
    if (o.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveEnd("character", o.value.length);
        if (r.text == "") return o.value.length;
        return o.value.lastIndexOf(r.text);
    } else return o.selectionStart;
}

function sessionStatusModal(val) {
    Swal.fire({
        toast: true,
        title: val.title,
        text: val.text,
        animation: false,
        position: "bottom",
        showConfirmButton: false,
        timer: 2000,
        position: "top-right",
        timerProgressBar: true,
        customClass: {
            popup: val.status == "success" ? "success" : "error",
        },
        html:
            `
        <div class="custom-toast">
                <span class="custom-toast-close"><img src="../images/icons/alert-cross-icon.svg" alt="cross"></span>
                <div class="custom-toast-content">

            <p>` +
            val.text +
            `</p>
          </div>
        </div>
        `,

        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });
}

function netherlandformatCurrency(value) {
    function formatNumber(n) {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Don't validate empty input
    if (!value && value !== 0) {
        return "";
    }

    if (value.toString().includes(".")) {
        const parts = value.toString().split(".");
        const leftSide = formatNumber(parts[0]);
        const rightSide = parts[1] ? parts[1].slice(0, 2) : "00";

        return leftSide + "," + rightSide;
    } else {
        return formatNumber(value) + ",00";
    }
}

$(".nav-link").on("click", function (e) {
    e.preventDefault();
});

// Handle tab switching using jQuery
$("#roles").on("click", function () {
    $("#permissions").removeClass("active");
    $("#roles").addClass("active");

    $("#tnav-permissions").removeClass("show active");
    $("#nav-roles").addClass("show active");
});

$("#permissions").on("click", function () {
    $("#roles").removeClass("active");
    $("#permissions").addClass("active");

    $("#nav-roles").removeClass("show active");
    $("#nav-permissions").addClass("show active");
});

$(document).on("click", ".international", function () {
    $(".select2ww").select2("close");
});

$(document).on("click", ".line_item_country", function () {
    $(".employee_data").select2("close");
});

document.addEventListener("livewire:load", function () {
    Livewire.on("setProjectDonorId", (projectDonorId) => {
        $("#project_create_user").val(projectDonorId).trigger("change");
    });
    /** select box for create project  */
    $(".select2ww").on("change", function (e) {
        if ($(this).attr("wire:model") == "project_donor_id") {
            Livewire.emit("updateprojectDonorId", e.target.value);
            Livewire.emit("updatedProjectDonorId", e.target.value);
            Livewire.emit(
                "editprojectDonorId",
                $("#project_create_user").val()
            );
            internationalCountry(".phone_modal");
        }
    });

    // $('#select2-element-id').select2();

    Livewire.on("refreshPopup", function () {});

    Livewire.on(
        "reinitializeSelect2",
        function (val, sub_project_id = null, data_id = null, user_id = null) {
            console.log(user_id);
            if (val != undefined) {
                $(".delete_project_row").trigger("click");

                if (sub_project_id == null) {
                    $(".add_employee_row[data-type-id='" + val + "']").trigger(
                        "click"
                    );
                } else {
                    $(
                        ".add_employee_row[data-type-id='" +
                            val +
                            "'][data-sub-project-id='" +
                            sub_project_id +
                            "'][data-id='" +
                            data_id +
                            "']"
                    ).trigger("click");
                }

                setTimeout(function () {
                    let id = $(".add_employee_row[data-type-id='" + val + "']")
                        .parentsUntil("tr")
                        .parent()
                        .siblings("tr.append-row")
                        .find("td:nth-child(2)")
                        .children("select")
                        .attr("id");

                    if (user_id != null) {
                        $("#" + id)
                            .val(user_id) // Set the select value to the user_id
                            .trigger("change");
                    } else {
                        $("#" + id)
                            .val($("#" + id + " option:eq(1)").val())
                            .trigger("change");
                    }
                }, 2500);
            }

            reloadselect2();
        }
    );
});

function reloadselect2() {
    $(".js-example-basic-single").each(function () {
        var id = $(this).attr("id");

        let usertypeIid = $(this).attr("data-user-type-id");

        let line_item_country = "line_item_country";

        let modal_user_type = "add_user_project";

        if (usertypeIid != undefined) {
            if (usertypeIid == 1) {
                line_item_country = "line_item_country";

                modal_user_type = "add_user_project";
            } else if (usertypeIid == 2) {
                line_item_country = "line_item_country1";

                modal_user_type = "add_consultant_project";
            } else if (usertypeIid == 3) {
                line_item_country = "line_item_country2";

                modal_user_type = "add_sub_grantee_project";
            }
        }
        $('[data-note="' + id + '"]').select2();

        $('[data-select22="' + id + '"]')
            .select2({
                width: "100%",
                allowClear: true,
                closeOnSelect: true,
                placeholder: "Select Option",
            })
            .on("select2:open", function () {
                let select2Instance = $(this).data("select2");
                if (!$(".select2-heading").length) {
                    let $heading = $(
                        '<div class="select2-heading">Select Option</div>'
                    );
                    select2Instance.$dropdown
                        .find(".select2-search")
                        .before($heading);
                }

                if (!$(".select2-link").length) {
                    let $searchInput = select2Instance.$dropdown.find(
                        ".select2-search input"
                    );
                    $searchInput.attr("placeholder", "Search Option");

                    select2Instance.$results
                        .parents(".select2-dropdown--below")
                        .find(".select2-search")
                        .append(
                            '<button class="select2-link btn btn-primary theme-btn flex-shrink-0 ' +
                                line_item_country +
                                '" data-bs-toggle="modal" data-bs-target="#' +
                                modal_user_type +
                                '"><img src="/images/icons/plus.svg" alt="add-icon" class="me-2 "/>Add new</button>'
                        );
                    $(".select2-heading").parent().addClass("bigdrop");
                }
            })
            .on("click", ".select2-search", function (e) {
                // Check if the clicked element is the button
                if ($(e.target).hasClass("select2-link")) {
                    // Button click event handler
                    alert("Button clicked!");
                }
            });
    });
}

$(document).on("click", "#reloadSelect2", function () {
    reloadselect2();
});

document.addEventListener("livewire:load", function () {
    $(document).on("click", ".delete_percentage", function () {
        let sub_project_id = $(this).attr("data-sub-project");
        let indirect_cost_percentage = null;
        if (sub_project_id == "0") {
            indirect_cost_percentage = $(
                '.indirect_cost[data-sub-project="0"]'
            ).val();
        } else {
            indirect_cost_percentage = $(
                ".indirect_cost[data-sub-project='" + sub_project_id + "']"
            ).val();
        }
        indirect_cost_percentage ? indirect_cost_percentage : 0;
        Livewire.emit("showPercentage", indirect_cost_percentage);
    });
});

function showLoader(button) {
    button.prop("disabled", true);

    button.closest(".button-loader").addClass("loading");
}

function removeLoader(button) {
    button.prop("disabled", false);
    button.closest(".button-loader").removeClass("loading");
}

// Indrect expenses events binders
document.addEventListener("livewire:load", function () {
    $(document).on("keypress", ".table-input", function (e) {
        if (e.keyCode === 13) {
            $(e.target).parent().find(".save-table-btn").trigger("click");
        }
    });
});
$(document).ready(function () {
    function initializeSelect2(selector, placeholderText) {
        $(selector).select2({
            placeholder: placeholderText,
            allowClear: true,
            dropdownParent: $("body"),
        });

        $(selector).on("select2:open", function () {
            setTimeout(() => {
                let dropdown = $(".select2-dropdown");
                let searchContainer = $(".select2-search--dropdown");

                // Ensure the custom title is added only once
                if (!dropdown.find(".custom-dropdown-title").length) {
                    dropdown.prepend(
                        `<div class="custom-dropdown-title" style="padding: 16px 16px 0px 16px;font-size: 16px; font-weight: 600;line-height: 24px;color: #1D2939;">${placeholderText}</div>`
                    );
                }

                // Set the placeholder for the search input
                $(".select2-search__field").attr(
                    "placeholder",
                    placeholderText
                );

                // Change flex direction of search input container
                searchContainer.css("flex-direction", "column");
            }, 10);
        });
    }

    initializeSelect2(".js-project-code", "Select project code");
    initializeSelect2(".doner-name", "Select donor");
});

$(document).ready(function () {
    $(".subproject-select").on("select2:open", function () {
        setTimeout(() => {
            $(".select2-results__options").addClass("custom-height");
        }, 10); // Slight delay to ensure the DOM is updated
    });
});
document.addEventListener("DOMContentLoaded", function () {
    // Handle changes for budget timeline inputs
    $("#current_budget_timeline_from").on("change", function () {
        Livewire.emit("updateCurrentBudgetTimelineFrom", this.value);
    });

    $("#current_budget_timeline_to").on("change", function () {
        Livewire.emit("updateCurrentBudgetTimelineTo", this.value);
    });

    $(document).on("change", ".selectProjectCodeJs", function () {
        let year = $(this).attr("wire:key");
        let value = $(this).val();
        Livewire.emit("updateSelectedCodes", year, value);
        document.addEventListener("livewire:load", function () {
            $(".selectProjectCodeJs").select2();
        });

        document.addEventListener("livewire:update", function () {
            $(".selectProjectCodeJs").select2();
        });
    });

    function initSelect2() {
        $(".js-example-basic-single").each(function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2();
            }
        });
    }
    Livewire.on("refreshSelectBox", () => {
        initSelect2();
    });

    // Re-initialize when modal is shown
    $(".modal").on("shown.bs.modal", function () {
        $(".subproject-select").select2({
            width: "100%",
            dropdownParent: $(this),
        });
    });
});

$(document).ready(function () {
    function formatOption(option) {
        if (!option.id) return option.text;
        var imgUrl = $(option.element).attr("data-image");
        if (!imgUrl) return option.text;

        return $(`<span><img src="${imgUrl}" /> ${option.text}</span>`);
    }

    $(".select-img").select2({
        templateResult: formatOption,
        templateSelection: formatOption,
        escapeMarkup: function (m) {
            return m;
        }, // Allow HTML rendering
    });
});

// Ensure Select2 initializes properly within all modals
$(document).on("shown.bs.modal", function (e) {
    const $modal = $(e.target); // Get the current open modal

    // Initialize Select2 inside the modal and fix the dropdown position
    $modal.find("select").each(function () {
        if (!$(this).data("select2")) {
            $(this).select2({
                dropdownParent: $modal, // Ensure dropdown is within the modal
                width: "100%", // Ensures it adapts to the parent width
            });
        } else {
            $(this).select2("destroy").select2({
                dropdownParent: $modal,
                width: "100%",
            });
        }
    });
});
