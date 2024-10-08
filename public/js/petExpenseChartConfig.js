let petRevenueChart = null;

async function GetRevenueData(){
  try {
    const selectValue = document.getElementById('petRevenueOptions').value;
    const response = await fetch('/pet/revenueChart/' + selectValue ?? 0);
    const data = await response.json();
    return data;
  } catch (error) {
    console.error(error);
  }
}

function CallGetRevenueData(){
  // Kiểm tra nếu biểu đồ đã tồn tại, hủy nó trước khi tạo mới
  if (petRevenueChart !== null) {
      petRevenueChart.destroy();
  }
  GetRevenueData().then(res=>{
    const data = {
      labels: res.labels,
      datasets: [
        {
          label: 'Thú cưng',
          data: res.PetRevenue,
          borderColor: '#FF0000',  // Mã màu đỏ
          backgroundColor: 'rgba(255, 0, 0, 0.5)',
        },
        {
          label: 'Phụ kiện',
          data: res.PetToolRevenue,
          borderColor: '#0000FF',
          backgroundColor: 'rgba(0, 0, 255, 0.5)',
        }
      ]
    };
    const config = {
      type: 'bar',
      data: data,
      options: {
        indexAxis: 'y',
        elements: {
          bar: {
            borderWidth: 2,
          }
        },
        responsive: true,
        plugins: {
          legend: {
            position: 'right',
          },
          title: {
            display: true,
            text: 'Biểu đồ doanh thu Thú cưng & Phụ kiện'
          }
        }
      },
    };
    petRevenueChart = new Chart("petRevenueChart", config);
  });  
}
CallGetRevenueData();