document.addEventListener('livewire:load', function () {
    Livewire.emit('budgetOverview');
    Livewire.on('indirectCostDataReceived', function (detail ) {
        var data = detail.budgetOverview
        // Highcharts.chart('budget_overview', {
        //     chart: {
        //         type: 'column'
        //     },
        //     title: {
        //         text: ''
        //     },
        //     xAxis: {
        //         categories: data.map(entry => entry.month),
        //         title: {
        //             text: ''
        //         },
        //         labels: {
        //             style: {
        //                 color: '#667085',
        //                 fontFamily: 'Inter',
        //                 fontSize: '12px',
        //                 fontWeight: '500',
        //                 lineHeight: '18px',
        //             }
        //         },
        //         lineColor: '#ffffff',
        //     },
        //     yAxis: {
        //         allowDecimals: false,
        //         title: {
        //             text: ''
        //         },
        //         gridLineDashStyle: 'dash',
        //         labels: {
        //             formatter: function () {
        //                 var value = parseFloat(this.value); // Convert to number
        //                 if (value >= 1000 && value < 10000) {
        //                     return '€' + (value / 1000) + 'K';
        //                 } else if (value >= 10000) {
        //                     return '€' + (value / 1000).toFixed(0) + 'K'; // Round to nearest thousand
        //                 } else {
        //                     return '€' + value;
        //                 }
        //             },
        //             style: {
        //                 color: '#667085', // Set the color of x-axis label
        //                 fontFamily: 'Inter',
        //                 fontSize: '12px',
        //                 fontWeight: '500',
        //                 lineHeight: '18px',
        //             }
        //         },
        //     },
        //     series: [{
        //             name: 'Direct costs',
        //             data: data.map(entry => entry.direct),
        //             color: '#EF831F',
        //             marker: {
        //                 symbol: 'square', // Change the marker symbol to square
        //             }
        //         },
        //         {
        //             name: 'Indirect costs',
        //             data: data.map(entry => entry.indirect),
        //             color: '#2C75FF',
        //             marker: {
        //                 symbol: 'square', // Change the marker symbol to square
        //             }
        //         },
        //     ],
        //     credits: {
        //         enabled: false
        //     },
        //     exporting: {
        //         enabled: false
        //     },
        //     legend: {
        //         itemStyle: {
        //             color: '#667085',
        //             fontFamily: 'Inter',
        //             fontSize: '14px',
        //             fontWeight: '500',
        //             lineHeight: '20px',
        //         },
        //         symbol: 'square', // Set the legend symbol to a square
        //         symbolPadding: 8, // Set padding between the symbol and text
        //         itemDistance: 24
        //     },
        // });

    let chart;
    function getSubtitle() {
        return `<div>Total Income</div>
        <br>
        <div style="color: #004677; font-family: inter; font-size: 20px; font-style: normal; font-weight: 600; line-height: 30px;">
            <b>€${netherlandFormatCurrency(detail.incomeChartData.totalBudgetSum)}</b>
        </div>`;
    }

    // function getSubtitle() {
    //     const totalBudget = detail.incomeChartData.totalBudgetSum;
    //     let formattedTotalBudget;
    //     if (totalBudget >= 1000) {
    //         const totalBudgetInK = totalBudget / 1000; // Dividing by 1000 to get the value in thousands
    //         formattedTotalBudget = netherlandFormatCurrency(totalBudgetInK) + "k";
    //     } else {
    //         formattedTotalBudget = netherlandFormatCurrency(totalBudget);
    //     }
    //     return `<div style="padding-bottom:4px;">Total Income</div>
    //         <br>
    //         <div style="color: #004677; font-family: inter; font-size: 20px; font-style: normal; font-weight: 600; line-height: 30px;">
    //             <b>€${formattedTotalBudget}</b>
    //         </div>`;
    // }

    chart = Highcharts.chart('income-by-donor', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        credits: {
            enabled: false
        },
        exporting: {
            enabled: false
        },
        subtitle: {
            // useHTML: true,
            text: getSubtitle(),
            // floating: true,
            // verticalAlign: 'middle',
            // y: -10,
            style: {
                color: '#667085',
                fontFamily: 'Inter',
                fontSize: '14px',
                fontWeight: '500',
                lineHeight: '20px',
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                innerSize: '80%',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true,
            }
        },
        legend: {
            itemStyle: {
                color: '#667085',
                fontFamily: 'Inter',
                fontSize: '12px',
                fontWeight: '500',
                lineHeight: '18px',
            },
            itemDistance: 24,
        },
        colors: detail.incomeChartData.chartData.map((_, i) => Highcharts.getOptions().colors[i % Highcharts.getOptions().colors.length]),
        series: [{
            name: 'Brands',
            data: detail.incomeChartData.chartData
        }]

    });

    // Find all elements with class 'highcharts-point'
    const elements = document.querySelectorAll('.highcharts-point');

    // Loop through each found element and update the 'rx' attribute
    elements.forEach((element) => {
        // Get the current 'rx' attribute value
        const rxValue = element.getAttribute('rx');
        // Check if the 'rx' attribute exists and update it to '0' if found
        if (rxValue !== null) {
            element.setAttribute('rx', '0');
        }
    });
    callSelect2();
    });
      
});