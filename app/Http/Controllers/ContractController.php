<?php

namespace App\Http\Controllers;

use App\Models\ROwnedBy;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Vendor;
use Ramsey\Uuid\Type\Integer;
use Str;
class ContractController extends Controller
{
    /**
     * Lay het hop dong
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetContract($from, $to){
        $contracts = [];
        $ownedBy = [];
        $contractVendors = [];
        $records = $this->neo4j->run($this->queryDatasource["Contract"]["GetContracts"], ["skipValue" => (int)$from, "limitValue" => (int)$to]);
        foreach ($records as $record){
            $contract = new Contract($record->get("c")->toArray());
            array_push($contracts, $contract);
            $ownedBy[$contract->contractId] = new ROwnedBy($record->get("o")->GetProperties()->toArray());
            $contractVendors[$contract->contractId] = new Vendor($record->get("v")->GetProperties()->toArray());
        }
        return view('contractView', ['contracts'=> $contracts, "ownedBy" => $ownedBy, "contractVendors" => $contractVendors]);
    }

    public function GetVendors(){
        $vendors = [];
        $records = $this->neo4j->run($this->queryDatasource["Contract"]["GetVendors"]);
        foreach ($records as $record){
            $vendor = new Vendor($record->get("c")->GetProperties()->toArray());
            array_push($vendors, $vendor);
        }
        return back()->with("vendors",$vendors);
    }

    public function GetDataSelectProductList($table){
        $data = [];
       switch($table){
        case "pet":
            $data = $this->neo4j->run("MATCH (n:pet) 
                                    WITH n.petName AS petName, n.petId AS petId 
                                    RETURN petName, petId
                                    ")->toArray();
        case "petTool":
            $data = $this->neo4j->run("MATCH (n:tool) 
                                    WITH n.toolName AS toolName, n.toolId AS toolId 
                                    RETURN toolName, toolId
                                    ")->toArray();
        case "food":
            $data = $this->neo4j->run("MATCH (n:Food) 
                                    WITH n.foodName AS foodName, n.foodId AS foodId 
                                    RETURN foodName, foodId
                                    ")->toArray();
        case "vendor":
            $data = $this->neo4j->run("MATCH (n:Vendor) 
                                        WITH n.vendorName AS vendorName, n.vendorId AS vendorId 
                                        RETURN vendorName, vendorId
                                        ")->toArray();
        return $data;
       }
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
        $product = $request->get("product");
        try{
            $result = 1;
            if($result > 0){
                return response()->json('Success', "Tạo hợp đồng thành công");
            }
        }catch(\Exception $e){
            return response()->json('error', "Có lỗi");
        }
        
        return response()->json('fail', "Tạo hợp đồng thất bại");

    }
    
    /**
     * Xoa hop dong
     * @param mixed $contractId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function DeleteContract(Request $request){
        try{
            $result = $this->neo4j->run($this->queryDatasource["Contract"]["DeleteContract"],["id" => $request->input('contractId')]);
            if($result > 0){
                return response()->json(['Inform' => "Xóa hợp đồng thành công"], 200 );
            }
        }catch(\Exception $e){
            return response()->json(['Inform' => "Xóa hợp đồng thất bại"+$request->input('contractId')], 500);
        }
        
        return response()->json(['Inform' => "Xóa hợp đồng thất bại"], 500);
    }

    /**
     * Xac nhan hop dong
     * @param mixed $contractId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function ConfirmContract(Request $request){
        try{
            $result = $this->neo4j->run($this->queryDatasource->Contract->CreateContract,["id" => $request->input('contractId')]);
            if($result > 0){
                return response()->json(['Inform' => "Cập nhật hợp đồng thành công" + $request->input('contractId')], 200 );
            }
        }catch(\Exception $e){
            return response()->json(['Inform' => "Cập nhật hợp đồng thất bại"], 500);
        }
        
        return response()->json(['Inform' => "Cập nhật hợp đồng thất bại"], 500);
    }
}
