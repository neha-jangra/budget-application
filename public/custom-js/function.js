$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});



function calculatePrice(
    unitCls,
    unitCostCls,
    approvedCostCls,
    actualExpenseCls,
    remainingBudgetCls,
    categoryId
) {
    var units = $("." + unitCls).val() || 0;
    var unitCost = $("." + unitCostCls).val() || 0;
    var actualExpense = $("." + actualExpenseCls).val() || 0;
    var approvedCost = convertDutchFormat(units) * convertDutchFormat(unitCost);
    var remainingCost = approvedCost - convertDutchFormat(actualExpense);
    $("." + approvedCostCls).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(approvedCost))
    );
    // remainingCost = Math.max(remainingCost, 0);
    $("." + remainingBudgetCls).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(remainingCost))
    );
    // if(categoryId>0){
    //     calculateTotalInExpense(categoryId)
    // }else{
    var indirectTotal = calculateTotalInExpense(categoryId);
    var directTotal = calculateTotalODExpense(categoryId);
    var totalAmount = parseFloat(indirectTotal) + parseFloat(directTotal);
    //}

    $(".indirectApprovedTotalJs" + categoryId).text(
        "€" + netherlandFormatCurrency(totalAmount)
    );
}

function calculateTotalODExpense(categoryId) {
    var approvedSum = 0;
    $(".OdApprovalBudgetJs" + categoryId).each(function () {
        var value = $(this).text().replace("€", "");
        var amount = parseFloat(removeDutchFormat(value)) || 0; // Convert value to float, default to 0 if NaN
        approvedSum += amount;
    });
    $(".odTotalApprovalCost" + categoryId).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(approvedSum))
    );

    var expenseSum = 0;
    $(".OdExpenseJs" + categoryId).each(function () {
        var value = $(this).val();
        var amount = parseFloat(removeDutchFormat(value)) || 0; // Convert value to float, default to 0 if NaN
        expenseSum += amount;
    });
    $(".odCostTillNow" + categoryId).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(expenseSum))
    );
    return convertDutchFormat(approvedSum);
}

function calculateTotalInExpense(categoryId) {
    var approvedSum = 0;
    $(".IeApprovedBudget" + categoryId).each(function () {
        var value = $(this).text().replace("€", "");
        var amount = parseFloat(removeDutchFormat(value)) || 0; // Convert value to float, default to 0 if NaN
        approvedSum += amount;
    });
    $(".IeTotalApprovalJs" + categoryId).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(approvedSum))
    );

    var expenseSum = 0;
    $(".IeActualExpenseJs" + categoryId).each(function () {
        var value = $(this).val();
        var amount = parseFloat(removeDutchFormat(value)) || 0; // Convert value to float, default to 0 if NaN
        expenseSum += amount;
    });
    $(".IeTotalExpense" + categoryId).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(expenseSum))
    );
    return convertDutchFormat(approvedSum);
}

function convertDutchFormat(value) {
    value = String(value);
    return value.split(",")[0].replace(/\./g, "");
}

function removeDutchFormat(inputNumber) {
    var parts = inputNumber.split(",");
    return parts[0].replace(/\./g, "");
}

function netherlandFormatCurrency(value) {
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

// Function to update the tab and content
function updateTab(tabName) {
    // Update the URL with the new query string
    var newUrl = window.location.href.split("?")[0] + "?active_tab=" + tabName;
    history.pushState(null, null, newUrl);

    // Find the tab element with data-bs-target equal to tabName
    var tabElement = document.querySelector(
        '[data-bs-target="#' + tabName + '"]'
    );

    if (tabElement) {
        // Simulate a click on the tab element
        tabElement.click();
    } else {
        console.error(
            'Tab element with data-bs-target="#' + tabName + '" not found.'
        );
    }
}

function allTabData(year) {
    $.ajax({
        url: "/indirect-all-tab?year=" + year,
        method: "GET",
        success: function (response) {
            $("#all-tab-detail").html(response);
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
    });
}

function saveReviseBudget(recordId, subProjectId) {
    let revised_unit_amount = $(".unitsAmountVal" + recordId).val();
    let revised_units = $(".unitsVal" + recordId).val();
    let revised_new_budget;
    if (revised_units == 0) {
        revised_new_budget = 0;
    } else {
        revised_new_budget = $(".newBudgetVal" + recordId).val();
    }
    let revised_annual = $(".revisedAnnualVal" + recordId).val();
    let sub_project_tab = $(".sub-project-result")
        .children(".active")
        .attr("id");
    $("#" + sub_project_tab).addClass("active");

    var resultData = {
        subProjectId: subProjectId,
        recordId: recordId,
        revised_annual: revised_annual,
        revised_unit_amount: revised_unit_amount,
        revised_units: revised_units,
        revised_new_budget: revised_new_budget,
        sub_project_tab: sub_project_tab,
    };
    Livewire.emit("saveRevisionData", resultData);
}

function calculateReviseBudget(
    unitCost,
    remainingBudget,
    expenseTillNow,
    recordId,
    event
) {
    var unit = parseFloat($(".revisedUnits" + recordId).val()) || 0;
    if (unitCost == 0) {
        unitCost = $(".revisedUnitCost" + recordId).val();
    }

    var unitsAmount = parseFloat(unit) * parseFloat(unitCost) || 0;
    var newBudget = parseFloat(remainingBudget) + unitsAmount || 0;
    var revisedAnnual = newBudget + parseFloat(expenseTillNow) || 0;
    if (unit > 0) {
        $(".unitsAmount" + recordId).text(
            netherlandFormatCurrency(convertDutchFormat(unitsAmount))
        );
        $(".newBudget" + recordId).text(
            netherlandFormatCurrency(convertDutchFormat(newBudget))
        );
        $(".revisedAnnual" + recordId).text(
            netherlandFormatCurrency(convertDutchFormat(revisedAnnual))
        );
        $(".unitsAmountVal" + recordId).val(unitsAmount);
        $(".unitsVal" + recordId).val(unit);
        $(".newBudgetVal" + recordId).val(newBudget);
        $(".revisedAnnualVal" + recordId).val(revisedAnnual);
    } else {
        $(".unitsAmount" + recordId).text(convertDutchFormat(unitsAmount));
        $(".newBudget" + recordId).text(convertDutchFormat(0));
        $(".revisedAnnual" + recordId).text(convertDutchFormat(revisedAnnual));
        $(".unitsAmountVal" + recordId).val(unitsAmount);
        $(".unitsVal" + recordId).val(unit);
        $(".newBudgetVal" + recordId).val(0);
        $(".revisedAnnualVal" + recordId).val(revisedAnnual);
    }
    if (event.keyCode == 13) {
        saveButton = $(event.target).siblings(".save-pill-revision");
        saveButton.click();
    }
}

function calculateReportEmployeeBudget(
    employeeId,
    amountJs,
    monthsCls,
    annualBudgetCls,
    projectedBudgetCls,
    balanceCls
) {
    var months = $("." + monthsCls + employeeId).val() || 0;
    var amount = $("." + amountJs + employeeId).val() || 0;
    var projectedBudget = $("." + projectedBudgetCls + employeeId).val() || 0;
    var annualBudget = convertDutchFormat(months) * convertDutchFormat(amount);
    var remainingCost = convertDutchFormat(projectedBudget) - annualBudget;
    $("." + annualBudgetCls + employeeId).val(annualBudget);
    $("." + balanceCls + employeeId).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(remainingCost))
    );
    if (removeDutchFormat(remainingCost) < 0) {
        $("." + balanceCls + employeeId).addClass("text-error-500");
    } else {
        $("." + balanceCls + employeeId).removeClass("text-error-500");
    }
}

function calculateReportEmployeeBudgetTotalAnnual(
    employeeId,
    annualBudgetCls,
    projectedBudgetCls,
    balanceCls
) {
    var projectedBudget = $("." + projectedBudgetCls + employeeId).val() || 0;
    var remainingCost = convertDutchFormat(
        projectedBudget -
            removeDutchFormat($("." + annualBudgetCls + employeeId).val())
    );
    $("." + balanceCls + employeeId).text(
        "€" + netherlandFormatCurrency(convertDutchFormat(remainingCost))
    );
    if (removeDutchFormat(remainingCost) < 0) {
        $("." + balanceCls + employeeId).addClass("text-error-500");
    } else {
        $("." + balanceCls + employeeId).removeClass("text-error-500");
    }
}

function saveReportsData(
    employeeId,
    amountJs,
    monthsCls,
    projectedBudgetCls,
    year,
    isOtherDirect,
    annualBudgetCls
) {
    let keyWithoutSpaces = employeeId.replace(/[^A-Za-z0-9\s]/g, "");
    var months = $("." + monthsCls + keyWithoutSpaces).val() || 0;
    var amount = $("." + amountJs + keyWithoutSpaces).val() || 0;
    var projectedBudget =
        $("." + projectedBudgetCls + keyWithoutSpaces).val() || 0;
    if (annualBudgetCls) {
        var annualBudget = convertDutchFormat(
            $(".totalAnnualBudgetJs" + annualBudgetCls).val()
        );
    } else {
        var annualBudget = convertDutchFormat(
            $(".totalAnnualBudgetJs" + employeeId).val()
        );
    }
    var remainingCost = annualBudget - convertDutchFormat(projectedBudget);
    var resultData = {
        employee_id: employeeId,
        monthly_amount: amount,
        months: months,
        total_annual_budget: annualBudget,
        projected_budget: projectedBudget,
        balance: remainingCost,
        year: year,
        is_other_direct: isOtherDirect,
    };
    console.log(resultData);
    $(".js-example-basic-single").select2();
    Livewire.emit("saveReportData", resultData);
}

function saveReportsDataIndirect(
    employeeId,
    projectedBudgetCls,
    year,
    isInDirect
) {
    let keyWithoutSpaces = employeeId.replace(/[^A-Za-z0-9\s]/g, "");
    var projectedBudget =
        $("." + projectedBudgetCls + keyWithoutSpaces).val() || 0;
    var annualBudget = convertDutchFormat(
        $(".totalAnnualBudgetCatJs" + employeeId).val()
    );
    var remainingCost = annualBudget - convertDutchFormat(projectedBudget);
    var resultData = {
        employee_id: employeeId,
        total_annual_budget: annualBudget,
        projected_budget: projectedBudget,
        balance: remainingCost,
        year: year,
        is_indirect: isInDirect,
    };
    $(".js-example-basic-single").select2();
    Livewire.emit("saveReportData", resultData);
}



//unit increase-decrease
function increaseValue(
    unitsCls,
    recordId,
    unitCost,
    expense,
    percentage,
    year,
    subProjectId,
    subProjectDataId,
    projectId
) {
    var val = $("." + unitsCls + recordId).val();
    var value = parseInt(val, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    $("." + unitsCls + recordId).val(value);
    calculateUnitsPrice(
        value,
        unitCost,
        expense,
        percentage,
        year,
        subProjectId,
        subProjectDataId,
        projectId,
        recordId
    );
}

function decreaseValue(
    unitsCls,
    recordId,
    unitCost,
    expense,
    percentage,
    year,
    subProjectId,
    subProjectDataId,
    projectId
) {
    var value = parseInt($("." + unitsCls + recordId).val(), 10);
    value = isNaN(value) ? 0 : value;
    value < 1 ? (value = 1) : "";
    value--;
    $("." + unitsCls + recordId).val(value);
    calculateUnitsPrice(
        value,
        unitCost,
        expense,
        percentage,
        year,
        subProjectId,
        subProjectDataId,
        projectId,
        recordId
    );
}

function calculateUnitsPrice(
    units,
    unitCost,
    expense,
    percentage,
    year,
    subProjectId,
    subProjectDataId,
    projectId,
    recordId
) {
    unitCost = parseFloat(unitCost) || "0";
    expense = parseFloat(expense) || "0";
    let approvedBudget = parseFloat(units) * parseFloat(unitCost);
    let remaining = approvedBudget - parseFloat(expense);
    var resultData = {
        sub_project_id: subProjectId,
        project_id: projectId,
        unit: units,
        unit_costs: unitCost,
        expenses: expense,
        remaining_balance: remaining,
        total_approval_budget: approvedBudget,
        sub_project_data_id: subProjectDataId,
        year: year,
        indirect_cost_percentage: percentage,
        recordId: recordId,
    };
    Livewire.emit("updateProjectUnits", resultData);
}

function switchYear(cls) {
    var value = $("." + cls).val();
    Livewire.emit("getYear", value);

    // Listen for the Livewire event completion or a custom event
    Livewire.on("yearLoaded", () => {
        // Call the functions that need to run after data is loaded
        hideElements();
        initializeAutoNumeric();
        callSelect2();
    });
    allTabData(value);
    callSelect2();
}

function switchTab(tab) {
    Livewire.emit("getActiveTab", tab);
    setTimeout(() => {
        callSelect2();
    }, 1000);
}

let autoNumericInstances = [];

function initializeAutoNumeric() {
 
    autoNumericInstances = AutoNumeric.multiple('input[data-type="currency"]', {
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
        unformatOnHover: true,
        selectOnFocus: false, // Disable selection on focus
    });
}

hideElements();

function hideElements() {
    $(".inner-level-table").css("display", "none");
    $(".inner-inner-level-table").css("display", "none");
}

//Toggle function for collapsing and expanding row
function collapseExpendRows(element) {
    var $parentRow = $(element).closest("tr");

    var $childrenRows = $parentRow.nextUntil("tr.details-row");
    $childrenRows.each(function () {
        var $childRow = $(this);
        var $innerTable = $childRow.find(".inner-level-table");
        var $innerInnerTable = $childRow.find(".inner-inner-level-table");

        // Check and handle collapse/expand for inner-level-table
        if ($innerTable.length && $parentRow.hasClass("outerTrJs")) {
            $innerTable.slideToggle("slow");
            var $arrow = $childRow.find(".accordionArrowJs");
            if ($arrow.length) {
                $arrow.toggleClass("rotate-90");
                $arrow.css(
                    "transform",
                    $arrow.hasClass("rotate-90")
                        ? "rotate(90deg)"
                        : "rotate(0deg)"
                );
            }
        }

        // Check and handle collapse/expand for inner-inner-level-table
        if ($innerInnerTable.length && $parentRow.hasClass("innerTrJs")) {
            $innerInnerTable.slideToggle("slow");
            var $innerArrow = $childRow.find(".innerAccordionArrowJs");
            if ($innerArrow.length) {
                $innerArrow.toggleClass("rotate-90");
                $innerArrow.css(
                    "transform",
                    $innerArrow.hasClass("rotate-90")
                        ? "rotate(90deg)"
                        : "rotate(0deg)"
                );
            }
        }
    });

    // Toggle arrow for the clicked element
    var $arrowImg = $parentRow.find(".accordionArrowJs");
    if ($arrowImg.length && $parentRow.hasClass("outerTrJs")) {
        $arrowImg.toggleClass("rotate-90");
        $arrowImg.css(
            "transform",
            $arrowImg.hasClass("rotate-90") ? "rotate(90deg)" : "rotate(0deg)"
        );
    }

    // Toggle arrow for the clicked element in inner-inner-level-table
    var $innerArrowImg = $parentRow.find(".innerAccordionArrowJs");
    if ($innerArrowImg.length && $parentRow.hasClass("innerTrJs")) {
        $innerArrowImg.toggleClass("rotate-90");
        $innerArrowImg.css(
            "transform",
            $innerArrowImg.hasClass("rotate-90")
                ? "rotate(90deg)"
                : "rotate(0deg)"
        );
    }
}

function callSelect2() {
    $(".js-example-basic-single").select2();
}

function scrollToBottom() {
    window.scrollTo({
        top: document.body.scrollHeight,
        behavior: "smooth",
    });
}

function collapseColumn(cls, parentCls) {
    $("." + parentCls).toggleClass(cls);
}

$(document).ready(function () {
    $("#generate-pdf").click(function () {
        var content = $("#content").html();

        // Send AJAX request to generate PDF
        $.ajax({
            url: "/report-pdf",
            type: "POST",
            data: {
                content: content,
            },
            success: function (response) {
                // Trigger download of generated PDF
                window.location.href = "{{ route('generate.pdf') }}";
            },
        });
    });
});

function updateUnits() {
    var input = $(this).parent().find(".table-input");
    var value = input.val().trim() === "" ? 0 : input.val();
    var employeeId = input.data("id");
    var categoryId = $(this).data("category");
    var rate = $(this).data("rate");
    var otherExpense = $(this).data("expense");
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
}

function updateCurrentYearExpenses() {
    var input = $(this).parent().find(".table-input");
    var value = input.val().trim() === "" ? 0 : input.val();
    var employeeId = input.data("id");
    var categoryId = $(this).data("category");
    var rate = $(this).data("rate");
    var otherExpense = $(this).data("expense");
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
}

$(document).on("click", ".updateUnitJs", updateUnits);
$(document).on("click", ".updateYearExpensesJs", updateCurrentYearExpenses);

function saveDetail(type, key, recordId, catId, valCls, rate) {
    var value = $("." + valCls).val();
    if (type !== "indirect") {
        rate = $(".OdUnitCost" + recordId + catId).val();
        Livewire.emit(
            "updateOtherIndirectExpenses",
            key,
            value,
            recordId,
            catId,
            rate
        );
    } else {
        Livewire.emit(
            "updateIndirectExpenses",
            key,
            value,
            recordId,
            catId,
            rate
        );
    }
}

function disableButton() {
    var button = $("#captureButton");
    button.prop("disabled", true);
    button.addClass("disabled");
    loader();
    // $('#downloadMessage').show();
    // Send AJAX request to the PDF URL
    $.ajax({
        url: button.attr("href"),
        method: "GET",
        success: function (response) {
            // Enable the button once the response is received
            setTimeout(() => {
                button.prop("disabled", false);
                button.removeClass("disabled");
                // $('#downloadMessage').hide();
            }, 1000);
        },
        error: function (xhr, status, error) {
            // Handle error if needed
            console.error(error);
        },
    });
}

function loader() {
    Swal.fire({
        toast: true,
        title: "Success",
        text: "We are exporting the pdf...",
        animation: false,
        position: "bottom",
        showConfirmButton: false,
        timer: 7000,
        position: "top-right",
        timerProgressBar: true,
        customClass: {
            popup: "success",
        },
        html:
            `<div class="custom-toast">
                    <span class="custom-toast-close"><img src="/../images/icons/alert-cross-icon.svg" alt="cross"></span>
                    <div class="custom-toast-content">
            
                <p>` +
            `We are exporting the pdf...` +
            `</p>
            </div>
            </div>`,
    });
}

var currencyMap = {
    EUR: "€",
    USD: "$",
    GBP: "£",
};

function convertCurrency(currency, amount, rate) {
    if (rate) {
        return amount * rate;
    } else {
        return null;
    }
}

function processCurrencyConversion() {
    var currency = $("#projectCurrency").val();
    var rate = parseFloat($("#XE_rate").val());
    if (rate == undefined || isNaN(rate) == true) {
        return;
    }
    $(".currencyConvertionJs").each(function () {
        var amount = $(this).data("original-amount");
        if (currency != "EUR") {
            var convertedAmount = convertCurrency(currency, amount, rate);
            $(this).text(currencyMap[currency] + convertedAmount.toFixed(2));
        } else {
            $(this).text("€" + convertDutchFormat(amount));
        }
    });
}

// Call processCurrencyConversion on change
$("#projectCurrency, #XE_rate").on("change", processCurrencyConversion);

function setSubProjectData(subProjectId, subProjectName) {
    // Set the input fields in the modal directly with the data
    document.getElementById("sub_project_name_input").value = subProjectName;
    document.getElementById("sub_project_id_input").value = subProjectId;
    console.log(
        "subProjectName-->",
        subProjectName,
        "subProjectId-->",
        subProjectId
    );
    // Emit event to inform Livewire about the data change
    Livewire.emit("setSubProjectData", subProjectId, subProjectName);
}




