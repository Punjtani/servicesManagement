<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\PriceIncrement;
use App\Models\ClientPricing;
use App\Models\ServiceSubCategoryAppartmentPrice;

class YearlyIncrementCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yearly:increment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentYear = Carbon::now()->format('Y');
        $yearlyIncrement = PriceIncrement::where(['year' => $currentYear,'is_already_incremented' => 0])->with('service')->with('service.subServices')->with('property')->get();
        if($yearlyIncrement){
            foreach($yearlyIncrement as $yearlyPrice){
                if($yearlyPrice['property']){
                    $propertyServicePrices = ClientPricing::where(['service_type_id'=>$yearlyPrice->service_id,'property_id'=>$yearlyPrice['property']->id])->get();
                    foreach($propertyServicePrices as $servicePrice){
                        $servicePrice->price = $servicePrice->price + $servicePrice->price * ($yearlyPrice->percentage/100);
                        $servicePrice->update();                    
                    }
                }
                $yearlyPrice->is_already_incremented = 1;
                $yearlyPrice->update();
            }
        }
        return 0;
        // if($yearlyIncrement){
        //     foreach($yearlyIncrement as $value){
        //         foreach($value['service']['subServices'] as $subService){
        //             $subServiceGeneralPrices = ServiceSubCategoryAppartmentPrice::where('sub_service_id',$subService->id)->get();
        //             foreach($subServiceGeneralPrices as $subServicePrice){
        //                 $subServicePrice->base_price = $subServicePrice->base_price + $subServicePrice->base_price * ($value->percentage/100);
        //                 $subServicePrice->update();                    
        //             }
        //             // $subServicePropertyPrices = ClientPricing::where('sub_service_id',$subService->id)->get();
        //             // foreach($subServicePropertyPrices as $subServicePrice){
        //             //     $subServicePrice->price = $subServicePrice->price + $subServicePrice->price * ($value->percentage/100);
        //             //     $subServicePrice->update();                    
        //             // }
        //         }
        //         $value->is_already_incremented = 1;
        //         $value->update();
        //     }
        // }

        // commented old logic
        // if($yearlyIncrement){
        //     foreach($yearlyIncrement as $yearlyPrice){
        //         if($yearlyPrice['client']['properties'] && count($yearlyPrice['client']['properties']) > 0){
        //             foreach($yearlyPrice['client']['properties'] as $property){
        //                     $propertyServicePrices = ClientPricing::where(['service_type_id'=>$yearlyPrice->service_id,'property_id'=>$property->id])->get();
        //                     foreach($propertyServicePrices as $servicePrice){
        //                         $servicePrice->price = $servicePrice->price + $servicePrice->price * ($yearlyPrice->percentage/100);
        //                         $servicePrice->update();                    
        //                     }
        //             }
        //         }
        //         $yearlyPrice->is_already_incremented = 1;
        //         $yearlyPrice->update();
        //     }
        // }
        // return 0;
    }
}
