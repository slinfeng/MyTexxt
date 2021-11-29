<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait FillFileInfo{

    public function fillFileInfo($fileInfo,$file)
    {
        $originalName = $fileInfo->getClientOriginalName();
        $ext = $fileInfo->getClientOriginalExtension();
        $name = substr($originalName,0,strrpos($originalName,'.'.$ext));
        $fileSize=$fileInfo->getSize();
        $file->user_id=Auth::user()->id;
        $file->thumbnail=''; //缩略图
        $file->name=$name;
        $file->basename=$originalName;
        $file->mimetype=$fileInfo->getClientOriginalExtension();
        $file->filesize=$fileSize;
        $file->type=$ext;
    }

    public function fillFileInfoWhenLocal($fileInfo,$file)
    {
        $this->fillFileInfo($fileInfo,$file);
        $file->is_in_local = 1;
    }

    public function fillFileInfoWhenCloud($fileInfo,$file)
    {
        $this->fillFileInfo($fileInfo,$file);
        $file->is_in_local = 0;
    }
}
