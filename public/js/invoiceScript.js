async function ViewDetail(id){
    try {
      const response = await fetch('/hoa-don/chi-tiet/'+id, {method: 'GET'});
      const data = await response.json();
      return data;
    } catch (error) {
      console.error(error);
    }
  }
function GetViewDetail(id) {
    ViewDetail(id).then(res=>{
        var productList = "";
        res["productList"].forEach((e) => {
            productList += `<div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Đối tác: </dt><dd class="text-gray-500 dark:text-gray-400">
                ${e["productName"]} - SL: ${e["quantity"]} - Giá: ${e["price"]}
                </div>`;
        });
        let content = `
            <div class="grid grid-cols-3 gap-4 mb-4 sm:mb-5">
                ${productList == "" ? "Không có thông tin": productList}
            </div>
            <dl class="grid grid-cols-2 gap-4 mb-4">
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Mã GD: </dt><dd class="text-gray-500 dark:text-gray-400 vendorViewInfo">${res["transaction"]["transactionId"]}</dd></div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Phương thức & tiền nhận: </dt><dd class="text-gray-500 dark:text-gray-400 vendorViewInfo">${res["transaction"]["type"]} - ${res["transaction"]["moneyRecieved"]}</dd></div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Trạng thái: </dt><dd class="text-gray-500 dark:text-gray-400 vendorViewInfo">${res["transaction"]["status"] == "Completed" ? "Hoàn tất" : "Chưa"}</dd></div>
            </dl>`;
        document.getElementById('view-detail-invoice').innerHTML = content;
    });
}
function DeleteAll(){
    if(confirm("Xác nhận xóa") === true){
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/hoa-don/xoa-het', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ month: document.getElementById("invoiceOptions").value }), // Send the ID in the request body
        })
        .then(response=>response.json())
        .then(response=>alert(response.Inform))
        .catch(err => alert(err));
        document.getElementById("invoice-view-block").innerHTML = "";
        document.getElementById("totalInvoice").innerText = "";
        document.getElementById("totalRevenue").innertext = "";
    }
}

function ReloadData(){
    window.location.href = "/hoa-don/" + document.getElementById("invoiceOptions").value;
}   