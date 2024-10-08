<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\RPurchase;
use App\Models\Transaction;
use Carbon\Carbon;
class InvoiceController extends Controller
{
    /**
     * 
     * Lay het hoa don theo thang, default = thang hien tai
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetInvoice($month = null){
        $year = date("Y");
        $month = $month == '0' ? Carbon::now()->format('m') : $month;
        $dateCriteria = $year.'-'.$month.'.*';
        $invoices = [];
        foreach($this->neo4j->run($this->queryDatasource["Invoice"]["GetInvoices"],["date" => $dateCriteria]) as $invoice){
            $inv = new Invoice($invoice->get("i")->toArray());
            array_push($invoices, $inv);
        }
        return view('invoiceView', compact("invoices"));
    }

    /**
     * Xoa hoa don bang id hoa don
     * @param mixed $invoiceId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function DeleteAll(){
        $data = json_decode(file_get_contents("php://input"), true);
        try{
            $change = $this->neo4j->run($this->queryDatasource["Invoice"]["DeleteAll"],["date" => $data["month"]]);
            return response()->json("success", "Thành công");
        }catch(\Exception $e){
            return response()->json('error', "Có lỗi");
        }
    }

    /**
     * Lay chi tiet thong tin hoa don: hoa don, san pham mua, giao dich
     * @param mixed $invoiceId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function GetDetailInvoice($invoiceId){
        $data = $this->neo4j->run($this->queryDatasource["Invoice"]["GetDetailInvoice"],["id" => $invoiceId])->first();
        if($data->isEmpty())
        {
            return response()->json(["transaction" => "", "productList" => ""]);
        }
        // lay tung phan data
        $transaction = $data->get('transaction')->toArray();
        $productList = $data->get('productList')->toArray();
        return response()->json(["transaction" => $transaction, "productList" => $productList]);
    }
   
}
