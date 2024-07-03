<?php

namespace App\Http\Controllers;

use App\Models\AppartmentType;
use App\Models\ClientPriceNotes;
use App\Models\ClientProperties;
use App\Models\ClientPropertyPriceNotes;
use Illuminate\Http\Request;

class ClientPriceNotesController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getClientPrice($serviceId)
    {
        $clientPriceNotes = ClientPriceNotes::where('service_id', $serviceId)->get();

        return response()->json([
            'clientPriceNotes' => $clientPriceNotes
        ], 200);
    }
    public function getClientPropertyPrice($id, $serviceId)
    {
        $clientPriceNotes = ClientPropertyPriceNotes::where(['property_id' => $id, 'service_id' => $serviceId])->get();

        return response()->json([
            'clientPriceNotes' => $clientPriceNotes
        ], 200);
    }
    /*
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function clientPricestore(Request $request)
    {
        $existingNotes = clientPriceNotes::where('service_id', $request->serviceId)->get();
        foreach ($request->notes_list as $r) {
            if (isset($r['id'])) {
                $data = ClientPriceNotes::find($r['id']);
                $data->description = $r['description'];
                $data->service_id = $request->serviceId;
                $data->save();

                foreach ($existingNotes as $key => $item) {
                    if ($item->id == $data->id) {
                        unset($existingNotes[$key]);
                    }
                }

                // for all property price notes
                $existingPropertyNotes = ClientPropertyPriceNotes::where(['service_id' => $request->serviceId, 'notes_id' => $data->id])->get();
                foreach ($existingPropertyNotes as $prop) {
                    $prop->description = $r['description'];
                    $prop->service_id = $request->serviceId;
                    $prop->save();
                }
            } else {
                if (isset($r['description'])) {
                    $data = new ClientPriceNotes();
                    $data->description = $r['description'];
                    $data->service_id = $request->serviceId;
                    $data->save();

                    // and for all the prices
                    $properties = ClientProperties::where('isActive', 1)->get();
                    foreach ($properties as $property) {
                        $value = new ClientPropertyPriceNotes();
                        $value->description = $r['description'];
                        $value->property_id = $property->id;
                        $value->notes_id = $data->id;
                        $value->service_id = $request->serviceId;
                        $value->save();
                    }
                }
            }
        }
        if ($existingNotes->count() > 0) {
            foreach ($existingNotes as $notes) {
                ClientPropertyPriceNotes::where('notes_id', $data->id)->delete();
                $notes->delete();
            }
            // $requestedJobService->each->RequestedJobSubService()->delete();
            // $requestedJobService->each->delete();
        }
        return response()->json([], 200);
    }
    public function clientPricesNoteDelete($id)
    {
        $client = ClientPriceNotes::find($id);
        return $client->delete();
    }

    public function clientPropertyPricestore(Request $request, $id)
    {
        $existingNotes = ClientPropertyPriceNotes::where(['property_id' => $id, 'service_id' => $request->serviceId])->get();

        foreach ($request->notes_list as $r) {
            if (isset($r['id'])) {
                $data = ClientPropertyPriceNotes::find($r['id']);
                $data->description = $r['description'];
                $data->service_id = $request->serviceId;
                $data->save();

                foreach ($existingNotes as $key => $item) {
                    if ($item->id == $data->id) {
                        unset($existingNotes[$key]);
                    }
                }
            } else {
                if (isset($r['description'])) {
                    $data = new ClientPropertyPriceNotes();
                    $data->description = $r['description'];
                    $data->service_id = $request->serviceId;
                    $data->property_id = $id;
                    $data->save();
                }
            }
        }
        if ($existingNotes->count() > 0) {
            foreach ($existingNotes as $notes) {
                $notes->delete();
            }
        }
        return response()->json([], 200);
    }
    public function getPropertyNotes($id, $serviceId)
    {
        $clientPriceNotes = ClientPropertyPriceNotes::where(['property_id' => $id, 'service_id' => $serviceId])->get();
        return response()->json([
            'notes' => $clientPriceNotes
        ], 200);
    }
}
