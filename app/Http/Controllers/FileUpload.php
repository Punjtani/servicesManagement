<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class FileUpload extends Controller
{
    public function store(Request $request){
        $files = $request->file('files');
        $filenames = [];
        $i=0;
        foreach($files as $file) {
            $current_date_time = Carbon::now()->timestamp;
            $destinationPath = 'uploads';
            $fileName = $i.$current_date_time.'.'.$file->getClientOriginalExtension();
            $file->move($destinationPath,$fileName);
            $filenames[]=  $destinationPath.'/'.$fileName;
            $i++;
        }
        return response()->json(['filenames' => $filenames]);

    }
}
