let petRevenueChart = null;

async function GetRevenueData() {
    try {
        const selectValue = document.getElementById('petRevenueOptions').value;
        const response = await fetch('/pet/revenueChart/' + selectValue ?? 0);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

function CallGetRevenueData() {
    // Kiểm tra nếu biểu đồ đã tồn tại, hủy nó trước khi tạo mới
    if (petRevenueChart !== null) {
        petRevenueChart.destroy();
    }
    GetRevenueData().then(res => {
        const total = res.Pet + res.PetTool + res.Food;
        const petPercentage = res.Pet == 0 ? 0 : ((res.Pet / total) * 100).toFixed(2);
        const petToolPercentage =  res.PetTool == 0 ? 0 :((res.PetTool / total) * 100).toFixed(2);
        const foodPercentage = res.Food == 0 ? 0 : ((res.Food / total) * 100).toFixed(2);
        const data = {
            labels: [petPercentage.toString() + '%', petToolPercentage.toString() + '%', foodPercentage.toString() + '%'],
            datasets: [
              {
                label: ['Thú cưng','Phụ kiện', 'Thức ăn'],
                data: [petPercentage, petToolPercentage, foodPercentage],
                backgroundColor: ['#ffa64d', '#ff99cc', '#1aa3ff'],
              }]
          };
          const config = {
            type: 'pie',
            data: data,
            options: {
              responsive: true,
              plugins: {
                legend: {
                  position: 'top',
                },
                title: {
                  display: true,
                  text: 'Biểu đồ doanh thu'
                }
              }
            },
          };
        petRevenueChart = new Chart("petRevenueChart", config);
    });
}
CallGetRevenueData();
