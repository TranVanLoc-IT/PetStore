<?php

namespace App\Http\Controllers;

use App\Models\RActiveOn;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Str;
class PromotionController extends Controller
{
    /**
     * Lay het khuyen mai
     * @var array
     */
    public function GetPromotion(){
        $promotions = [];
        $activeOns = [];
        $specifications = [];
        $productApplies = [];
        $records = $this->neo4j->run($this->queryDatasource["Promotion"]["GetPromotions"]);
        foreach ($records as $record){
            $promotion = new Promotion($record->Get("p")->GetProperties()->toArray());
            $activeOn[$promotion->promotionId] = new RActiveOn($record->Get("a")->toArray());
            $productApplies[$promotion->promotionId] = $record->Get("productApplies");
            $specifications[$promotion->promotionId] = $record->Get("specifications");
            array_push($promotions, $promotion);
        }
        return view('promotionView', ['promotions'=> $promotions, "productApplies"=> $productApplies, "activeOn"=> $activeOn, "specifications" => $specifications]);
    }

    /**
     * Tao moi khuyen mai
     * @param \App\Models\Promotion $promotion
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function CreatePromotion(Request $request){
        $query = $this->queryDatasource["Promotion"]["CreatePetToolPromotion"];
        if($request->input("productType") == "Pet")
        {
            $query = $this->queryDatasource["Promotion"]["CreatePetPromotion"];
        }
        $dataFields = [
            "promotionId"  => (string)Str::uuid(), 
            "value" => $request->input("value"), 
            "description" => $request->input("description"), // Sửa chính tả
            "title" => $request->input("title"),
            "dateStart" => $request->input("dateStart"),
            "dateEnd" => $request->input("dateEnd"),
            "criterias" => $request->input("node")
        ];
        
        try {
            $result = $this->neo4j->run($query, $dataFields);
            return response()->json(["Inform" => "Thành công", "promotionId" => $dataFields["promotionId"]],200);
        }catch(\Exception $e){
            return response()->json(['Inform' => "Có lỗi: " + $e->getMessage()], 404);
        }
    }

    /**
     * Xoa khuyen mai khi no  het han
     * @param mixed $promotionId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function DeletePromotion($id){
        $criteria = ["id" => $id];
        try{
            $result = $this->neo4j->run($this->queryDatasource["Promotion"]["DeletePromotion"],$criteria);
            return response()->json(['Inform'=>"Xóa khuyến mãi thành công"], 200);
        }catch(\Exception $e){
            return response()->json(['Inform'=>"Có lỗi"], 404);
        }

    }
}
