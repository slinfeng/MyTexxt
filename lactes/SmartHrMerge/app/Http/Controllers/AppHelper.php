<?php

namespace App\Http\Controllers;

class AppHelper extends Controller
{
    public function deleteFile($fileName)
    {
        if ($fileName != "default.jpg") {
            $image_path = "upload/" . $fileName;
            if (unlink("upload/" . $fileName)) {
                return true;
            } else {
                echo "No someone reach First:)";
            }
        }
    }
    public function saveImage($request)
    {
        $image = $request->file('icon');
        $input['imagename'] = uniqid() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/upload');
        $image->move($destinationPath, $input['imagename']);

        return $input['imagename'];
    }

    public function saveBase64($baseString)
    {
        $img = $baseString;
        $img = str_replace('data:image/jpg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        $data = base64_decode($img);
        $Iname = uniqid();
        $file = public_path('/upload/') . $Iname . ".jpg";
        $success = file_put_contents($file, $data);
        return $Iname . ".jpg";
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return number_format(($miles * 1.609344), 2, '.', '');
            } else if ($unit == "N") {
                return number_format(($miles * 0.8684), 2, '.', '');
            } else {
                return number_format($miles, 2, '.', '');
            }
        }
    }
}
