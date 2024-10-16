async function ViewDetail(id) {
    try {
        // Lấy dữ liệu mà k cần tải lại trang
        // Url này được cấu hình trong file Web.php gọi đến phương thức GetDetailInvoice
        const response = await fetch('/hoa-don/chi-tiet/' + id, {
            method: 'GET'
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

function GetViewDetail(id) {
    ViewDetail(id).then(res => {
        // Nhận dữ liệu và in ra HTML
        var productList = "";
        res["productList"].forEach((e) => {
            productList += `<div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Sản phẩm mua: </dt><dd class="text-gray-500 dark:text-gray-400">
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

function Delete(id) {
    if (confirm("Xác nhận xóa") === true) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/hoa-don/xoa', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id
                }), // Gửi id đến controller
            })
            .then(response => response.json())
            .then(response => {
                // nếu thành công
                SAlertMessage.innerText = response.Inform;
                SAlertBlock.classList.remove('hidden');

                // Sau 2 giây (2000ms), ẩn thông báo
                setTimeout(() => {
                    SAlertBlock.classList.add('hidden');
                }, 2000);
            })
            .catch(err => {
                // Nếu thất bại
                EAlertMessage.innerText = "Thất bại";
                EAlertBlock.classList.remove('hidden');

                // Sau 2 giây (2000ms), ẩn thông báo
                setTimeout(() => {
                    EAlertBlock.classList.add('hidden');
                }, 2000);
            });
            location.reload();
        // Xóa sạch các kết quả và số liệu
    }
}

function ReloadData() {
    // Tải lại trang
    window.location.href = "/hoa-don/" + document.getElementById("invoiceOptions").value;
}
