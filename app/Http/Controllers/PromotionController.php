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
        $productApplies = [];
        $records = $this->neo4j->run($this->queryDatasource["Promotion"]["GetPromotions"]);
        foreach ($records as $record){
            $promotion = new Promotion($record->Get("p")->GetProperties()->toArray());
            $activeOn[$promotion->promotionId] = new RActiveOn($record->Get("a")->toArray());
            $productApplies[$promotion->promotionId] = $record->Get("productApplies");
            array_push($promotions, $promotion);
        }
        return view('promotionView', ['promotions'=> $promotions, "productApplies"=> $productApplies, "activeOn"=> $activeOn]);
    }

    /**
     * Tao moi khuyen mai
     * @param \App\Models\Promotion $promotion
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function CreatePromotion(){
        $data = json_decode(file_get_contents('php://input'), true);

        try{
            $result = $this->neo4j->run($this->queryDatasource["Promotion"]["CreatePromotion"],[]);
            if($result > 0){
                return response()->json('Success', "Tạo khuyến mãi thành công");
            }
        }catch(\Exception $e){
            return response()->json('error', "Có lỗi");
        }
        
        return response()->json('fail', "Tạo khuyến mãi thất bại");
    }

    /**
     * Xoa khuyen mai khi no  het han
     * @param mixed $promotionId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function DeletePromotion($promotionId){
        try{
            $result = $this->neo4j->run($this->queryDatasource["promotion"]["Deletepromotion"],["id" => $promotionId]);
            return response()->json('Inform', "Xóa khuyến mãi thành công");
        }catch(\Exception $e){
            return response()->json('Inform', "Có lỗi");
        }
        
    }
}
