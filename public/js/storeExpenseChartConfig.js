let storeExpenseChart = null;

async function GetExpenseData(url) {
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

function LoadTotalReAndExData() {
    GetExpenseData("/store/totalReAndExData/" + document.getElementById("storeDataOptions").value)
        .then(res => {
            document.querySelector(".totalRevenue").textContent = Math.floor(res.data.totalRevenue);
            document.querySelector(".totalExpense").textContent = Math.floor(res.data.totalExpense);
            document.querySelector(".totalProfit").textContent = Math.floor(Number(res.data.totalRevenue) - Number(res.data.totalExpense));
        })
        .catch(error => {
            alert(error.message)
        });
}

function CallGetExpenseData() {
    // Kiểm tra nếu biểu đồ đã tồn tại, hủy nó trước khi tạo mới
    if (storeExpenseChart !== null) {
        storeExpenseChart.destroy();
    }
    GetExpenseData('/store/expenseChart/' + document.getElementById('storeExpenseOptions').value).then(res => {
        const data = {
            labels: res.labels,
            datasets: [{
                    label: 'Thu nhập',
                    data: res.expense,
                    borderColor: '#FF0000',
                    backgroundColor: 'rgba(255, 0, 0, 0.5)',
                },
                {
                    label: 'Chi tiêu',
                    data: res.revenue,
                    borderColor: '#0000FF',
                    backgroundColor: 'rgba(0, 0, 255, 0.5)',
                }
            ]
        };
        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Doanh thu theo tháng'
                    }
                }
            },
        };
        storeExpenseChart = new Chart("storeExpenseChart", config)
    });
}
CallGetExpenseData();
