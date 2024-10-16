let addProductBlock = "";
const PAGE_SIZE = 5;
let currentPage = 1;
let movePag = 0;
let currentPageElement = document.querySelector(".first-pag");
let to = PAGE_SIZE;
let from = currentPage * PAGE_SIZE;
async function ViewDetail(contractId) {
    try {
        const response = await fetch('/hop-dong/chi-tiet/' + contractId);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

async function GetDropdownData(table) {
    try {
        const response = await fetch('/hop-dong/du-lieu' + table);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

function GetViewDetail(contractId, vendorName, phone) {
    ViewDetail(contractId).then(res => {
        var productList = "";
        res.forEach((e) => {
            productList += `<div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Sản phẩm: </dt><dd class="text-gray-500 dark:text-gray-400">
                ${e["productName"]} - SL: ${e["totalAmount"]} - Giá: ${e["totalPrice"]}
                </div>`;
        });
        let content = `
            <div class="grid grid-cols-3 gap-4 mb-4 sm:mb-5">
                ${productList}
            </div>
            <dl class="grid grid-cols-2 gap-4 mb-4">
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Đối tác: </dt><dd class="text-gray-500 dark:text-gray-400 vendorViewInfo">${vendorName} - SDT: ${phone}</dd></div>
            </dl>`;
            if(movePag > 1)
            {
                document.getElementById('btn-show').click();
            }
        document.getElementById('view-detail-contract').innerHTML = content;
    });
}


function DeleteContract(id) {
    if (confirm("Xác nhận xóa") === true) {
        Delete(id);
        document.getElementById("row-" + id).remove();
    }
}

function ConfirmContract(id) {
    fetch('/hop-dong/update', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contractId: id
            }), // Send the ID in the request body
        })
        .then(response => response.json())
        .then(response => {
            let updateButtonStatus = document.getElementById("update-" + id);
            updateButtonStatus.innerHTML = `<button disabled type="button" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                            Đã duyệt
                                        </button>`;
            updateButtonStatus.classList.replace('bg-amber-700', 'bg-primary-700');
            updateButtonStatus.classList.replace('bg-amber-600', 'bg-primary-600');
            SAlertMessage.innerText = response.Inform;
            SAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                SAlertBlock.classList.add('hidden');
            }, 2000);
        })
        .catch(err => {
            EAlertMessage.innerText = "Thất bại";
            EAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                EAlertBlock.classList.add('hidden');
            }, 2000);
        });
}

function Delete(id) {
    fetch('/hop-dong/delete', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contractId: id
            }), // Send the ID in the request body
        })
        .then(response => response.json())
        .then(response => {
            data = data.map((e, index)=>{
                if(e[index].contractId === id) {
                    totalSize--;
                    return undefined; // xoa
                }
                else{
                    return e;
                }
            }).filter((e)=>e !== undefined);
            SAlertMessage.innerText = response.Inform;
            SAlertBlock.classList.remove('hidden');
            InitPagination();
            Pagination(currentPageElement, currentPage);
            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                SAlertBlock.classList.add('hidden');
                2
            }, 2000);
        })
        .catch(err => {
            EAlertMessage.innerText = "Thất bại";
            EAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                EAlertBlock.classList.add('hidden');
            }, 2000);
        });
}

document.getElementById('create-contract-form').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this);
    // Chuyển đổi FormData thành JSON
    let formObject = {};
    formData.forEach((value, key) => {
    formObject[key] = value;
    });
    fetch('/hop-dong/insert', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: formData, // Send the ID in the request body
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Lỗi");
            }
            return response.json();
        })
        .then(response => {
            SAlertMessage.innerText = "Tạo thành công";
            SAlertBlock.classList.remove('hidden');
            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                SAlertBlock.classList.add('hidden');
            }, 2000);
            location.reload();
        })
        .catch(err => {
            EAlertMessage.innerText = "Thất bại";
            EAlertBlock.classList.remove('hidden');

            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                EAlertBlock.classList.add('hidden');
            }, 2000);
        });
    this.reset();

});


function InitPagination() {
    if (PAGE_SIZE > totalSize) {
        document.querySelector('.no-pag').classList.remove('hidden');
        document.querySelectorAll('.pag-des')[1].innerText = `1 - ${totalSize} trong ${totalSize} kết quả`;
    } else {
        let hPag = document.querySelector('.have-pag')
        hPag.classList.remove('hidden');
        for (let i = 2; i < (totalSize / PAGE_SIZE) + 1; i++) {
            hPag.innerHTML += `<li>
                                <a onclick="Pagination(this,${i})" class="flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">${i}</a>
                            </li>`;
        }
        hPag.innerHTML += ` <li>
                                <button id="next" onclick="Pagination(this,'next')" class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <span class="sr-only">Tiếp</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </li>`;
        document.querySelectorAll('.pag-des')[0].innerText = `${((PAGE_SIZE * currentPage) - PAGE_SIZE) + 1} - ${PAGE_SIZE * currentPage} trong ${totalSize} kết quả`;
    }
}
InitPagination();

function AddNewTableRow(dataTable, data){
    let tr = document.createElement('tr');
        tr.id = `row-${data.contractId}`;
        tr.className = 'border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700';
        tr.innerHTML = `<td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center mr-3">
                                ${data.contractId}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center">
                                ${data.title}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${data.totalCost}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ${data.signingDate}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ${

                                DATA_VENDOR[data.contractId].confirm ?`
                                    <button class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-primary-700 rounded-lg hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                        Đã duyệt
                                    </button>`
                                :
                                `<button id="update-${data.contractId}" onclick="ConfirmContract('${data.contractId}')" type="button" data-drawer-target="drawer-update-product" data-drawer-show="drawer-update-product" aria-controls="drawer-update-product" class="py-2 px-3 flex items-center text-sm font-medium text-center text-white bg-amber-700 rounded-lg hover:bg-amber-800 focus:ring-4 focus:outline-none focus:ring-amber-300 dark:bg-amber-600 dark:hover:bg-amber-700 dark:focus:ring-amber-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                    </svg>
                                    Chờ duyệt
                                </button>`
                            }
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center space-x-4">
                                    <button onclick="GetViewDetail('${data.contractId}','${DATA_VENDOR[data.contractId].vendorName}','${DATA_VENDOR[data.contractId].phone}')" type="button" id="viewProductButton" data-modal-toggle="viewContractModel" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-2 -ml-0.5">
                                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                        </svg>
                                        Xem
                                    </button>
                                    
                                    <button onclick="DeleteContract('${data.contractId}')" type="button" class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Xóa
                                    </button>
                                </div>
                            </td>`;
        dataTable.appendChild(tr);
}

function Pagination(e, index) {
    movePag++;
    to = PAGE_SIZE;
    let pageLeft = totalSize % PAGE_SIZE;
    if(PAGE_SIZE <= totalSize)
    {
        // ktra truoc, sau
        if (isNaN(index)) {
            if (index == "previous") {
                currentPage--;
                if (currentPage == 0)
                    document.getElementById('previous').disabled = true; // vo hieu hoa neu het trang
                if(currentPage < 0)
                    return;
            } else {
                currentPage++;
                if (currentPage > (totalSize / PAGE_SIZE))
                {
                    to = pageLeft;                
                    document.getElementById('next').disabled = true;
                }
            }
        }else{
            currentPage = index;
            if (currentPage > (totalSize / PAGE_SIZE))
            {
                to = pageLeft; // lay cac trang du con lai
                document.getElementById('next').disabled = true;
            }
            if(currentPage == 1)
            {
                document.getElementById('previous').disabled = true; // vo hieu hoa neu het trang
            }
        }
    }
    // xac dinh trang dich
    from = (currentPage * PAGE_SIZE) - PAGE_SIZE + 1;
    // neu trang vuot qua trang dang co hoac < 0 => disabled button
    if(currentPage > (totalSize / PAGE_SIZE) && (totalSize % PAGE_SIZE) == 0) return;
    let dataTable = document.getElementById('contract-table-body');
    dataTable.innerHTML = "";
    if (e != null) {
        currentPageElement.classList.remove('bg-green-300', 'opacity-50');
        currentPageElement = e;
        currentPageElement.classList.add('bg-green-300', 'opacity-50');
    }
    // neu trang cuoi cung thi lay so trang chia du + kich thuoc trang
    for (let count = (currentPage - 1) * PAGE_SIZE; count < (to == pageLeft ? to + PAGE_SIZE : PAGE_SIZE * currentPage); count++) {
        AddNewTableRow(dataTable, data[count]);
    }
    document.querySelectorAll('.pag-des')[0].innerText = `${from} - ${to == pageLeft ? totalSize : to} trong ${totalSize} kết quả`;

}
Pagination(null, 1);

function CreateVendorInfoForm() {
    const typeOpt = document.getElementById('vendorType');
    const companyOpt = document.getElementById('vendorId');
    const sellerOpt = document.getElementById('cVendor');
    if (typeOpt.value == "company") {
        companyOpt.innerHTML = "";
        GetDropdownData("/vendor").then(data => {
            data.forEach(vendor => {
                companyOpt.innerHTML += `<option value="${vendor.vendorId}">${vendor.vendorName}</option>`
            });
        });
        companyOpt.classList.remove("hidden");
        if (IsDisplayCreateVendorInfoForm(sellerOpt)) {
            sellerOpt.classList.add("hidden");
        }
    } else {
        sellerOpt.classList.remove("hidden");
        if (IsDisplayCreateVendorInfoForm(companyOpt)) {
            companyOpt.classList.add("hidden");
        }
    }
}

function IsDisplayCreateVendorInfoForm(form) {
    if (form.classList.contains("hidden")) {
        return false;
    }
    return true;
}

function LoadCreateProductListForm() {
    const createOpt = document.getElementById('productType');
    const createForm = document.getElementById('productSupplies');
    let dropdownContent = "";
    switch (createOpt.value) {
        case "food":
            GetDropdownData("/food").then(data => {
                data.forEach(food => {
                    dropdownContent += `<option value="${food["foodId"]}">${food["foodName"]}</option>`
                });
                AssignCreateProductContent(createForm, dropdownContent);
            });
            break;
        case "pet":
            GetDropdownData("/pet").then(data => {
                data.forEach(pet => {
                    dropdownContent += `<option value="${pet["petId"]}">${pet["petName"]}</option>`
                });
                AssignCreateProductContent(createForm, dropdownContent);
            });
            break;
        case "tool":
            GetDropdownData("/petTool").then(data => {
                data.forEach(petTool => {
                    dropdownContent += `<option value="${petTool["toolId"]}">${petTool["toolName"]}</option>`
                });
                AssignCreateProductContent(createForm, dropdownContent);
            });
            break;

    }

}

function AssignCreateProductContent(createForm, dropdownContent) {
    addProductBlock = `<div class="flex flex-wrap space-x-4 items-center mt-2">
    <label class="text-sm font-medium text-gray-900 dark:text-white">Sản phẩm</label>
    <select onchange="RenderInputForm(this.value)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
        <option selected>Chọn</option>
        ${dropdownContent}
    </select>

    <label for="quantity[]" class="text-sm font-medium text-gray-900 dark:text-white">Số lượng: </label>
    <input type="number" name="quantity[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Số lượng" required="">

    <label for="cost[]" class="text-sm font-medium text-gray-900 dark:text-white">Giá nhập: </label>
    <input type="number" name="cost[]" onchange="CalulateTotalCost()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Giá nhập" required="">
</div>`;
}


function RenderInputForm(value) {
    var input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('class', 'hidden');
    input.setAttribute('value', value);
    input.setAttribute('name', 'product[]');
    document.getElementById('create-contract-form').appendChild(input);
}

function AddCreateProductInfoBlock(value) {
    const createForm = document.getElementById('productSupplies');
    createForm.innerHTML = "";
    for (let i = 0; i < value; i++) {
        createForm.innerHTML += addProductBlock;
    }
}

function CalulateTotalCost() {
    // Lấy tất cả các phần tử có id là 'priceImport[]'
    const priceInputs = document.querySelectorAll('input[name="cost[]"]');
    const quantityInputs = document.querySelectorAll('input[name="quantity[]"]');

    let total = 0;

    // Duyệt qua các phần tử và tính tổng
    priceInputs.forEach(function (input, index) {
        const value = parseFloat(input.value) || 0; // Chuyển đổi giá trị thành số (nếu không thì là 0)
        total += value * quantityInputs[index].value;
    });

    // Hiển thị tổng giá trị
    document.getElementById('totalCost').value = total;
}

// tim kiem
document.getElementById('search-bar').setAttribute('placeholder', "Nhập mã hợp đồng");
document.getElementById('search-bar').addEventListener('change',function () {
    var index = -1;
    // duyet tim
    for(let i = 0; i < totalSize; i++)
    {
        if(data[i].contractId.toUpperCase() == this.value.toUpperCase()){
            index = i;
            break;
        }
    }

    if(index == -1)
    {
        EAlertMessage.innerText = "Không tìm thấy hợp đồng !";
            EAlertBlock.classList.remove('hidden');
            // Sau 2 giây (2000ms), ẩn thông báo
            setTimeout(() => {
                EAlertBlock.classList.add('hidden');
            }, 2000);
            return;
    }

    let dataTable = document.getElementById('contract-table-body');
    dataTable.innerHTML = "";
    AddNewTableRow(dataTable, data[index]);
    });