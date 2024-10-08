
let storeExpenseChart = null;

async function GetExpenseData(){
  try {
    const selectValue = document.getElementById('storeExpenseOptions').value;
    const response = await fetch('/store/expenseChart/' + selectValue);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
  }
}

function CallGetExpenseData(){
  // Kiểm tra nếu biểu đồ đã tồn tại, hủy nó trước khi tạo mới
  if (storeExpenseChart !== null) {
      storeExpenseChart.destroy();
  }
  GetExpenseData().then(res=>{
    const data = {
      labels: res.labels,
      datasets: [
        {
          label: 'Thu nhập',
          data: res.expense,
          borderColor: '#FF0000',  // Mã màu đỏ
          backgroundColor: 'rgba(255, 0, 0, 0.5)',
        },
        {
          label: 'Chi tiêu',
          data:  res.revenue,
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