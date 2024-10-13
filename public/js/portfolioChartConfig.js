async function GetData(url, month) {
    month = month == 0 ? new Date().getMonth() + 1 : month;
    try {
        const response = await fetch(url + '/' + month);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

function LoadTableData() {
    let month = document.getElementById('portfolioOptions').value;
    const value = document.getElementById('tableOption').value;
    let content = "";
    if (value == "/san-pham") {
        GetData(value, month).then(data => {
            data.products.forEach((e, index) => {
                let ivnlist = "";
                data.invoice[index].forEach(i => {
                    ivnlist += i + ' ';
                });
                content += `
        <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="h-10 w-10 mr-3 bg-gray-100 rounded-full overflow-hidden">
                      <img src="../img/pet/${e.img}" alt="Annette Watson profile picture">
                    </div>
            </th>
            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center mr-3">
                ${e.petId??e.foodId??e.toolId} - ${e.petName??e.toolName??e.foodName}
                </div>
            </th>
            <td class="px-4 py-3">
                <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300 ${e.petId ?? e.foodId ?? e.toolId}-price">${e.price}</span>
            </td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                <div class="flex items-center">
                    <div class="h-4 w-4 rounded-full inline-block mr-2 bg-orange-500"></div>
                    ${e.availableQuantity}
                </div>
            </td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${Number(data.totalSold[index])}</td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${ivnlist}</td>
            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            ${data.totalRevenue[index]}
            </td>
            <td>
                <input type="number" name="newPrice" class="form-control">
                <button onclick="UpdateProductPrice('${e.petId ?? e.foodId ?? e.toolId}', this.previousElementSibling.value)">Sửa</button>
            </td>
           
        </tr>`;
            })
            tableView.innerHTML = `<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="p-4">Hình ảnh</th>
            <th scope="col" class="p-4">Tên sản phẩm</th>
            <th scope="col" class="p-4">Giá bán</th>
            <th scope="col" class="p-4">Số lượng còn</th>
            <th scope="col" class="p-4">Số lượng đã bán</th>
            <th scope="col" class="p-4">Danh sách hóa đơn</th>
            <th scope="col" class="p-4">Doanh thu</th>
            <th scope="col" class="p-4">Cập nhật giá</th>
        </tr>
        </thead><tbody>${content}</tbody>`;
        });

    } else {
        GetData(value, month).then(data => {
            let dateWorkCol = [];
            data.staffs.forEach((e, index) => {
                data.shiftWorks[index].forEach(i => {
                    if (!dateWorkCol.includes(`<th class='px-2 py-1 text-sm w-auto text-center'>${i.date}</th>`) && i.date != null)
                        dateWorkCol.push(`<th class='px-2 py-1 text-sm w-auto text-center'>${i.date}</th>`);
                });
            });
            data.staffs.forEach((e, index) => {
                let dateWorkCell = "";
                let sumOfHours = 0;
                data.shiftWorks[index].forEach(i => {
                    if (i != null)
                        sumOfHours += i.hour;
                });
                // tạo dòng dữ liệu theo cột                
                dateWorkCol.forEach(element => {
                    let cell = "";
                    data.shiftWorks[index].forEach(d => {
                        if (element.includes(d.date)) {
                            cell = `<td>${d.phase}-${d.hour}</td>`;
                            return;
                        }
                        if (d.date != null) {
                            cell = `<td>0</td>`;
                        }
                    })
                    dateWorkCell += cell;
                });
                content += `
            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                
                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="flex items-center mr-3">
                        ${e.active == 1 ? '<div class="w-4 h-4 bg-green-500 rounded-full"></div>':'<div class="w-4 h-4 bg-red-500 rounded-full"></div>'}
                    </div>
                </th>
                <td class="px-4 py-3">
                    <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-primary-900 dark:text-primary-300">${e.staffId} - ${e.staffName}</span>
                </td>
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <div class="flex items-center">
                        <div class="h-4 w-4 rounded-full inline-block mr-2 bg-orange-500"></div>
                        ${e.yearIn}
                    </div>
                </td>
                
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${data.role[index]}</td>
                ${dateWorkCell}
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${Math.floor(data.salary[index])}</td>
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${sumOfHours}</td>
                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${Math.floor(sumOfHours * data.salary[index])}</td>
                <td class="font-medium text-gray-900 whitespace-nowrap dark:text-white ${e.staffId}-payStatus">
    ${data.paid[index] != null 
        ? data.paid[index] 
        : `<button class="bg-amber-300 w-full h-full block p-5" onclick="PaySalary('${e.staffId}', ${sumOfHours * data.salary[index]})">Trả lương</button>`}
</td>


               
            </tr>`;
            })
            tableView.innerHTML = `<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="p-4">Tình trạng</th>
            <th scope="col" class="p-4">Mã & tên nhân viên</th>
            <th scope="col" class="p-4">Ngày vào làm</th>
            <th scope="col" class="p-4">Vai trò</th>
            ${dateWorkCol.join(' ') != 'NULL' ? dateWorkCol.join(' ') : ""}
            <th scope="col" class="p-4">Lương theo giờ</th>
            <th scope="col" class="p-4">Tổng giờ làm</th>
            <th scope="col" class="p-4">Lương tháng</th>
            <th scope="col" class="p-4">Thao tác</th>
        </tr>
    </thead><tbody>${content}</tbody>`;
        });
    }
}

function PaySalary(staffId, value) {
    let month = document.getElementById('portfolioOptions').value;
    fetch('/nhan-vien/paysalary/' + month, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                money: value,
                staffId: staffId
            }), // Send the ID in the request body
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Lỗi");
            }
            return response.json();
        }).then(response => {
            document.querySelector(`.${staffId}-payStatus`).textContent = response.Inform;
            SAlertMessage.innerText = response.Inform;
            SAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                SAlertBlock.classList.add('hidden');
            }, 2000);

        })
        .catch(err => {
            EAlertMessage.innerText = err;
            EAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                EAlertBlock.classList.add('hidden');
            }, 2000);
        });
}

function UpdateProductPrice(id, value) {
    fetch('/san-pham/update/price', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                productId: id,
                newPrice: value
            }), // Send the ID in the request body
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Lỗi");
            }
            return response.json();
        }).then(response => {
            document.querySelector(`.${id}-price`).textContent = value;
            SAlertMessage.innerText = response.Inform;
            SAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                SAlertBlock.classList.add('hidden');
            }, 2000);

        })
        .catch(err => {
            EAlertMessage.innerText = err;
            EAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                EAlertBlock.classList.add('hidden');
            }, 2000);
        });
}
