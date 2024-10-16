<?php

namespace App\Http\Controllers;

use App\Models\ROwnedBy;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Vendor;
use Ramsey\Uuid\Type\Integer;
use Str;
use Log; // ghi log ở file storage/logs/laravel.log
class ContractController extends Controller
{

    /**
     * Dùng để thay thế các tham số cho việc tạo quan hệ khi tạo contract
     * @param mixed $query
     * @param mixed $param
     * @return array|string
     */
    private function GetCreateContractQueryString($query, $param){
        return str_replace(array_keys($param),array_values($param), $query);
    }

    /**
     * Lấy hết hợp đồng
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetContract($type){
        $filter = [];
        $query = $this->queryDatasource["Contract"]["GetContracts"];
        if($type != "all"){
            $query = $this->queryDatasource["Contract"]["GetContractUnconfirmed"];
            $filter["criteria"] = false;
            if($type == "confirmed"){
                $query = $this->queryDatasource["Contract"]["GetContractConfirmed"];
                $filter["criteria"] = true;
            }
        }
        $contracts = [];
        $ownedBy = [];
        $contractVendors = [];
        $records = $this->neo4j->run($query, $filter);
        foreach ($records as $record){
            $contract = new Contract($record->get("c")->toArray());
            array_push($contracts, $contract);
            $contractVendors[$contract->contractId] = $record->get("vendor");
        }
        return view('contractView', ['contracts'=> $contracts, "contractVendors" => $contractVendors]);
    }

    /**
     * Lấy danh sách các nhà cung cấp
     * @return \Illuminate\Http\RedirectResponse
     */
    public function GetVendors(){
        $vendors = [];
        $records = $this->neo4j->run($this->queryDatasource["Contract"]["GetVendors"]);
        foreach ($records as $record){
            $vendor = new Vendor($record->get("c")->GetProperties()->toArray());
            array_push($vendors, $vendor);
        }
        return back()->with("vendors",$vendors);
    }

    /**
     * Lấy thông tin danh sách các sản phẩm cho dữ liệu tạo sản phẩm cho contract
     * @param mixed $table
     * @return mixed
     */
    public function GetDataSelectProductList($table){
        $data = [];
       switch($table){
        case "pet":
            $data = $this->neo4j->run("MATCH (n:Pet) 
                                    WITH n.petName AS petName, n.petId AS petId
                                    RETURN petName, petId
                                    ")->toArray();
                                    break;

        case "petTool":
            $data = $this->neo4j->run("MATCH (n:PetTool) 
                                    WITH n.toolName AS toolName, n.toolId AS toolId 
                                    RETURN toolName, toolId
                                    ")->toArray();
                                    break;

        case "food":
            $data = $this->neo4j->run("MATCH (n:Food) 
                                    WITH n.foodName AS foodName, n.foodId AS foodId 
                                    RETURN foodName, foodId
                                    ")->toArray();
                                    break;
        case "vendor":
            $data = $this->neo4j->run("MATCH (n:Vendor) 
                                        WITH n.vendorName AS vendorName, n.vendorId AS vendorId 
                                        RETURN vendorName, vendorId
                                        ")->toArray();
                                    break;
        }
        return $data;
    }

    /**
     * Lay het chi tiet hop dong
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetDetailContract($id){
        $products = [];
        $records = $this->neo4j->run($this->queryDatasource["Contract"]["GetDetailContract"], ["id" => $id]);
        foreach ($records as $record){
            array_push($products, $record->get("p"));
        }   
        return $products;
    }

    /**
     * Tao hop dong
     * @param \App\Models\Contract $contract
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function CreateContract(Request $request){
        $query = $this->queryDatasource["Contract"]["CreateVendorContract"];
        $dataFields = [
            'contractId' => (string)Str::uuid(), 
            'title' => $request->input("title"), 
            'totalCost' => $request->input("totalCost"), 
            'signingDate' => $request->input("signingDate"), 
            'description' => $request->input("description") == null ? "" : $request->input("description"),
            'vendorId' => (int)$request->input("vendorId"),
            'totalQuantity' => $request->input("productQuantity"),
            'image' => ''
        ];
        if($request->input("vendorType") == "customer")
        {
            $query = $this->queryDatasource["Contract"]["CreateCustomerContract"];
            $dataFields['sellerName'] = $request->input("sellerName");
            $dataFields['phone'] = $request->input("phone");
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $dataFields["image"] = $image->storeAs('images', $imageName, 'public/img/contract'); // Lưu vào storage/app/public/images
        }

        $contractQuery = $this->GetCreateContractQueryString($this->queryDatasource["Contract"]["FindContractNode"], ['$contractId' => $dataFields["contractId"]]);
        $size = $request->input('productQuantity'); // Đếm số lượng sản phẩm
        $param = [];
        try {
            $this->neo4j->run($query, $dataFields);

        if($request->input('productType') == "pet")
        {
            for($i = 0; $i < $size; $i++){
                $param['$totalCost'] = $request->input("cost")[$i] * $request->input('quantity')[$i];
                $param['$totalAmount'] = $request->input("quantity")[$i];
                $param['$id'] = $request->input("product")[$i];
                $relationshipQuery = $contractQuery;
                $relationshipQuery .= $this->GetCreateContractQueryString($this->queryDatasource["Contract"]["CreateContractToPetRelationship"], $param);
                $this->neo4j->run($relationshipQuery);
            }
        }
        else if($request->input('productType') == "tool")
        {
            for($i = 0; $i < $size; $i++){
                
                $param['$totalCost'] = $request->input("cost")[$i] * $request->input('quantity')[$i];
                $param['$totalAmount'] = $request->input("quantity")[$i];
                $param['$id'] = $request->input("product")[$i];
                $relationshipQuery = $contractQuery;
                $relationshipQuery .= $this->GetCreateContractQueryString($this->queryDatasource["Contract"]["CreateContractToPetToolRelationship"], $param);
                $this->neo4j->run($relationshipQuery);
            }
        }
        else{
            for($i = 0; $i < $size; $i++){
               
                $param['$totalCost'] = $request->input("cost")[$i] * $request->input('quantity')[$i];
                $param['$totalAmount'] = $request->input("quantity")[$i];
                $param['$id'] = $request->input("product")[$i];
                $relationshipQuery = $contractQuery;
                $relationshipQuery .= $this->GetCreateContractQueryString($this->queryDatasource["Contract"]["CreateContractToFoodRelationship"], $param);
                $this->neo4j->run($relationshipQuery);

            }
        }
            return response()->json(['Inform' => $dataFields["contractId"]],200);

        }catch(\Exception $e){
            return response()->json(['Inform' => "Có lỗi cú pháp"], 404);
        }
    }
    
    /**
     * Xoa hop dong
     * @param mixed $contractId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function DeleteContract(){

    $data = json_decode(file_get_contents('php://input'), true);
        try{
            $result = $this->neo4j->run($this->queryDatasource["Contract"]["DeleteContract"],["id" => $data["contractId"]]);
            return response()->json(['Inform' => "Xóa hợp đồng thành công"], 200 );
        }catch(\Exception $e){
            return response()->json(['Inform' => "Xóa hợp đồng thất bại"], 500);
        }
    }

    /**
     * Xac nhan hop dong
     * @param mixed $contractId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function UpdateContract(){
    $data = json_decode(file_get_contents('php://input'), true);
        try{
            $result = $this->neo4j->run($this->queryDatasource["Contract"]["ConfirmContract"],["id" => $data['contractId']]);
            return response()->json(['Inform' => "Cập nhật hợp đồng thành công"], 200 );
        }catch(\Exception $e){
            return response()->json(['Inform' => "Cập nhật hợp đồng thất bại"], 500);
        }
    }

}
