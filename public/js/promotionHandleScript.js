function Delete(id) {
    fetch('/khuyen-mai/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi xóa dữ liệu');
            }
            return response.json();
        })
        .then(response => {
            document.getElementById(`promotion-${id}`).remove();
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

async function GetDropdownData(url) {
    try {
        const response = await fetch('/hop-dong/du-lieu' + url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error(error);
    }
}

document.getElementById('create-promotion-form').addEventListener('submit', function (event) {
    event.preventDefault();
    const formData = new FormData(this); // Lấy dữ liệu từ form
    // Lấy giá trị của dateStart và dateEnd
    const dateStart = formData.get('dateStart');
    const dateEnd = formData.get('dateEnd');
    // Kiểm tra giá trị dateStart và dateEnd có hợp lệ không
    if (new Date(dateStart) >= new Date(dateEnd)) {
        EAlertMessage.innerText = "Ngày bắt đầu phải nhỏ hơn ngày kết thúc!";
        EAlertBlock.classList.remove('hidden');

        // Sau 2 giây (2000ms), ẩn thông báo
        setTimeout(() => {
            SAlertBlock.classList.add('hidden');
        }, 2000);

        event.preventDefault();
        return;
    }
    fetch('/khuyen-mai', {
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
            let isActive = `<div class="h-4 w-4 rounded-full inline-block mr-2 bg-orange-500"></div>`;
            if(new Date(dateEnd) > Date.now()) {
                isActive = `<div class="h-4 w-4 rounded-full inline-block mr-2 bg-green-500"></div>`
            }
            let selectedValues = formData.getAll("node[]");  // Lấy các giá trị đã chọn
            let selectedTexts = [];

            selectedValues.forEach(value => {
                let option = document.querySelector(`option[value="${value}"]`);
                if (option) {
                    selectedTexts.push(option.text);  // Lấy text của option đã chọn
                }
            });
            document.getElementById('promotionTableBody').innerHTML += `<tr id="promotion-${response.promotionId}" class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center mr-3">
                                ${response.promotionId}
                                </div>
                            </th>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center">
                                    ${isActive}
                                    ${ formData.get('title')}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${ formData.get('description')}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ${selectedTexts.join(', ')}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">${ formData.get('value')}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                              ${ formData.get('dateStart')} - ${ formData.get('dateEnd')}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
0
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            0
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
0
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center space-x-4">
                                    <button onclick="Delete('{{$promotion->promotionId}}')" type="button" class="flex items-center text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-0.5" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Xóa
                                    </button>
                                </div>
                            </td>
                        </tr>`;
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
})


function LoadCreateProductListForm() {
    const createOpt = document.getElementById('productType');
    const createForm = document.getElementById('productActiveOn');
    let dropdownContent = "";
    switch (createOpt.value) {
        case "Pet":
            GetDropdownData("/pet").then(data => {
                data.forEach(pet => {
                    dropdownContent += `<option value="${pet["petId"]}">${pet["petName"]}</option>`
                });
                createForm.innerHTML = `<div class="flex flex-wrap space-x-4 items-center mt-2">
                  <label for="category" class="text-sm font-medium text-gray-900 dark:text-white">Sản phẩm</label>
                  <select name="node[]" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                      ${dropdownContent}
                  </select>`;
            });

            break;
        case "PetTool":
            GetDropdownData("/petTool").then(data => {
                data.forEach(petTool => {
                    dropdownContent += `<option value="${petTool["toolId"]}">${petTool["toolName"]}</option>`
                });
                createForm.innerHTML = `<div class="flex flex-wrap space-x-4 items-center mt-2">
                  <label for="category" class="text-sm font-medium text-gray-900 dark:text-white">Sản phẩm</label>
                  <select name="node[]" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                      ${dropdownContent}
                  </select>`;
            });
            break;
    }
}

function AddNewProductSupply(event) {
    event.preventDefault();
    const createForm = document.getElementById('productActiveOn');
    createForm.innerHTML += createForm.innerHTML;
}
