<?php
namespace App\Exports;
use App\Models\Parcel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Session;
class ParcelExport implements FromCollection

{

    /**

    * @return \Illuminate\Support\Collection

    */

    public function collection()

    {
        
         $startDate = request()->input('startDate');
        $endDate   = request()->input('endDate');
        $status   = request()->input('status');
        $mid   = request()->input('mid');
        return \DB::table('parcels')
        ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
        ->leftJoin('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
        ->join('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
        ->where('parcels.status',$status)
         ->orWhere('parcels.merchantId',$mid)
        ->orWhereBetween('present_date', [ $startDate, $endDate] )
        ->select('merchants.companyName','parcels.invoiceNo','parcels.recipientName','parcels.trackingCode','parcels.recipientPhone','deliverycharges.slug','recipientAddress','nearestzones.zonename','agents.name','parcels.present_date','parcels.updated_at','parceltypes.title','parcels.cod','parcels.codCharge','parcels.deliveryCharge')->get();

    }
    public function map($parcel) : array {

        return [

            $parcel->recipientName,
            $parcel->user->recipientPhone,
            $parcel->user->recipientAddress,

        ] ;

 

 

    }

}