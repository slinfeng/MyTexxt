<?php

namespace App\Traits;

use Illuminate\Support\Facades\Gate;

trait ThumbnailTrait{
    private function image_resize($imagedata, $width, $height, $per = 0) {
        // 1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM
        // 获取图像信息
        list($bigWidth, $bigHight, $bigType) = getimagesizefromstring($imagedata);
        // 缩放比例
        if ($per > 0) {
            $width  = $bigWidth * $per;
            $height = $bigHight * $per;
        }
        // 创建缩略图画板
        $block = imagecreatetruecolor($width, $height);
        // 启用混色模式
        imagealphablending($block, false);
        // 保存PNG alpha通道信息
        imagesavealpha($block, true);
        // 创建原图画板
        $bigImg = imagecreatefromstring($imagedata);
        // 缩放
        imagecopyresampled($block, $bigImg, 0, 0, 0, 0, $width, $height, $bigWidth, $bigHight);
        // 生成临时文件名
        $tmpFilename = tempnam(sys_get_temp_dir(), 'image_');
        // 保存
        switch ($bigType) {
            case 1: imagegif($block, $tmpFilename);
                break;
            case 2: imagejpeg($block, $tmpFilename);
                break;
            case 3: imagepng($block, $tmpFilename);
                break;
        }
        // 销毁
        imagedestroy($block);
        $image = file_get_contents($tmpFilename);
        unlink($tmpFilename);
        return $image;
    }
}
