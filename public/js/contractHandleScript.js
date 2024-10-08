async function ViewDetail(contract){
    try {
      const response = await fetch('/hop-dong/chi-tiet/'+contract["contractId"]);
      const data = await response.json();
      return data;
    } catch (error) {
      console.error(error);
    }
  }
async function GetDropdownData(url){
    try {
        const response = await fetch('/hop-dong/du-lieu'+url);
        const data = await response.json();
        return data;
      } catch (error) {
        console.error(error);
      }
}
function GetViewDetail(contract, vendor) {
    contract = JSON.parse(contract);
    ViewDetail(contract).then(res=>{
        var productList = "";
        res.forEach((e) => {
            productList += `<div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Đối tác: </dt><dd class="text-gray-500 dark:text-gray-400">
                ${e["productName"]} - SL: ${e["totalAmount"]} - Giá: ${e["totalPrice"]}
                </div>`;
        });
        let content = `
            <div class="grid grid-cols-3 gap-4 mb-4 sm:mb-5">
                ${productList}
            </div>
            <dl class="grid grid-cols-2 gap-4 mb-4">
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Đối tác: </dt><dd class="text-gray-500 dark:text-gray-400 vendorViewInfo">${vendor["vendorName"]}</dd></div>
                <div class="p-3 bg-gray-100 rounded-lg border border-gray-200 dark:bg-gray-700 dark:border-gray-600"><dt class="mb-2 font-semibold leading-none text-gray-900 dark:text-white">Tổng giá trị: </dt><dd class="text-gray-500 dark:text-gray-400 totalCostViewInfo">${contract["totalCost"]}</dd></div>
            </dl>`;
        document.getElementById('view-detail-contract').innerHTML = content;
    });
}


function DeleteContract(id){
    if(confirm("Xác nhận xóa") === true){
        Delete(id);
        document.getElementById("row-"+ id).remove();
    }
}

function ConfirmContract(id){
        fetch('/hop-dong/edit', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ contractId: id }), // Send the ID in the request body
        })
        .then(response=>response.json())
        .then(response=>{
            let updateButtonStatus = document.getElementById("update-" + id);
            updateButtonStatus.innerText = 'Duyệt';
            updateButtonStatus.classList.replace('bg-amber-700', 'bg-primary-700');
            updateButtonStatus.classList.replace('bg-amber-600', 'bg-primary-600');
            alert(response.Inform);
        })
        .catch(err => alert(err));
}

function Delete(id){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/hop-dong/edit', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ contractId: id }), // Send the ID in the request body
        })
        .then(response=>response.json())
        .then(response=>alert(response.Inform))
        .catch(err => alert(err));
}

function CreateContract(){
    const formData = new FormData(document.getElementById('create-contract-form'));
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('/hop-dong/edit', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData), // Send the ID in the request body
        })
        .then(response=>response.json())
        .then(response=>alert(response.Inform))
        .catch(err => alert(err));
}

function CreateVendorInfoForm(){
    const typeOpt = document.getElementById('vendorType');
    const companyOpt = document.getElementById('vendorId');
    const sellerOpt = document.getElementById('cVendor');
    if(typeOpt.value == "company"){
        companyOpt.innerHTML = "";
        GetDropdownData("/vendor").then(data => {
            data.forEach(vendor => {
                companyOpt.innerHTML += `<option value="${vendor.vendorId}">${vendor.vendorName}</option>`
            });
        });
        companyOpt.classList.remove("hidden");
        if(IsDisplayCreateVendorInfoForm(sellerOpt)){
            sellerOpt.classList.add("hidden");
        }
    }
    else{
        sellerOpt.classList.remove("hidden");
        if(IsDisplayCreateVendorInfoForm(companyOpt)){
            companyOpt.classList.add("hidden");
        }
    }
}

function IsDisplayCreateVendorInfoForm(form){
    if(form.classList.contains("hidden")){
        return false;
    }
    return true;
}

function LoadCreateProductListForm(){
    const createOpt = document.getElementById('productType');
    const createForm = document.getElementById('productSupplies');
    let dropdownContent = "";
    switch(createOpt.value){
        case "food":
            GetDropdownData("/food").then(data => {
                data.forEach(food => {
                    dropdownContent += `<option name="productName[]" id="productName[]" value="${food["foodId"]}">${food["foodName"]}</option>`
                });
                AssignCreateProductContent(createForm, dropdownContent);
            });
            break;
            case "pet":
                GetDropdownData("/pet").then(data => {
                    data.forEach(pet => {
                        dropdownContent += `<option name="productName[]" id="productName[]" value="${pet["petName"]}">${pet["petName"]}</option>`
                    });
                    
                });
                AssignCreateProductContent(createForm, dropdownContent);
            break;
            case "tool":
                GetDropdownData("/petTool").then(data => {
                    data.forEach(petTool => {
                        dropdownContent += `<option name="productName[]" id="productName[]" value="${petTool["toolId"]}">${petTool["toolName"]}</option>`
                    });
                    
                });
                AssignCreateProductContent(createForm, dropdownContent);
            break;
            
    }
    
}
function AssignCreateProductContent(createForm, dropdownContent) {
    createForm.innerHTML = `<div class="flex flex-wrap space-x-4 items-center mt-2">
    <label for="category" class="text-sm font-medium text-gray-900 dark:text-white">Sản phẩm</label>
    <select id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
        <option selected>Chọn</option>
        ${dropdownContent}
    </select>

    <label for="quantity[]" class="text-sm font-medium text-gray-900 dark:text-white">Số lượng: </label>
    <input type="number" name="quantity[]" id="quantity[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Số lượng" required="">

    <label for="priceImport" class="text-sm font-medium text-gray-900 dark:text-white">Giá nhập: </label>
    <input type="number" name="priceImport[]" id="priceImport[]" onchange="CalulateTotalCost()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Giá nhập" required="">
</div>
                    `;
}
function AddNewProductSupply(event){
    event.preventDefault();
    const createForm = document.getElementById('productSupplies');
    createForm.innerHTML += createForm.innerHTML;
}

function CalulateTotalCost(){
      // Lấy tất cả các phần tử có id là 'priceImport[]'
      const priceInputs = document.querySelectorAll('input[id="priceImport[]"]');
      const quantityInputs = document.querySelectorAll('input[id="quantity[]"]');
    
      let total = 0;
      
      // Duyệt qua các phần tử và tính tổng
      priceInputs.forEach(function(input, index) {
          const value = parseFloat(input.value) || 0; // Chuyển đổi giá trị thành số (nếu không thì là 0)
          total += value  * quantityInputs[index].value;
      });
  
      // Hiển thị tổng giá trị
      document.getElementById('totalCost').value = total;
  }