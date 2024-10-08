<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\RPurchase;
use App\Models\Transaction;

class InvoiceController extends Controller
{
    /**
     * 
     * Lay het hoa don theo thang, default = thang hien tai
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function GetInvoice(Request $request){
        $year = date("Y");
        $month = $request->input('month') == 0 || is_null($request->input('month'))? date('m') : $request->input('month');
        $dateCriteria = $year.'-'.$month.'.*';
        $invoices = [];
        foreach($this->neo4j->run($this->queryDatasource["Invoice"]["GetInvoices"],["date" => $dateCriteria]) as $invoice){
            array_push($invoices, $invoice);
        }
        return view('invoiceView', compact("invoices"));
    }

    /**
     * Xoa hoa don bang id hoa don
     * @param mixed $invoiceId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function DeleteInvoice($invoiceId){
        try{
            $change = $this->neo4j->run($this->queryDatasource["Invoice"]["DeleteInvoice"],["id" => $invoiceId]);
            if($change->count() > 0){
                return response()->json("success", "Thành công");
            }
        }catch(\Exception $e){
            return response()->json('error', "Có lỗi");
        }
        return response()->json("fail", "Thất bại");

    }

    /**
     * Lay chi tiet thong tin hoa don: hoa don, san pham mua, giao dich
     * @param mixed $invoiceId
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function GetDetailInvoice($invoiceId){
        $data = $this->neo4j->run($this->queryDatasource["Invoice"]["GetSpecificInvoice"],["id" => $invoiceId]);
        // lay tung phan data
        $invoice = new Invoice($data->get('i')->getProperties()->toArray());
        $transaction = new Transaction($data->get('t')->getProperties()->toArray());
        $purchase = new RPurchase($data->get('r')->getProperties()->toArray());
        $product = $data->get('p')->getProperties()->toArray();
        return response()->json(["invoice" => $invoice, "transaction" => $transaction, "purchase" => $purchase, "product" => $product]);
    }
   
}
