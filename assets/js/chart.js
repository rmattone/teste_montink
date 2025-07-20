const barChart = (id, barChartData) => {
    const ctx = $(id);
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: barChartData,
        options: {
            elements: {
                rectangle: {
                    borderWidth: 0.1,
                    borderColor: 'rgb(255, 255, 255)',
                    borderSkipped: 'bottom'
                }
            },
            responsive: true,
            legend: {
                position: 'top',
            }
        }
    });

}
const lineChart = (id, barChartData) => {
    const ctx = $(id);
    const myChart = new Chart(ctx, {
        type: 'line',
        data: barChartData,
        options: {
            elements: {
                rectangle: {
                    borderWidth: 0.5,
                    borderColor: 'rgb(255, 255, 255)',
                    borderSkipped: 'bottom'
                }
            },
            responsive: true,
            legend: {
                position: 'top',
            },
        }
    });

}