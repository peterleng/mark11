<?php
/**
 * User: Peter Leng
 * DateTime: 2017/5/24 19:44
 */

namespace App\Lib\Util;


class ImageCode
{
    private $image;   //图像资源
    private $disturbColorNum;
    private $codeStr;

    /**
     * 输出验证码
     *
     * @param int $width
     * @param int $height
     * @param int $length
     */
    public function build($width = 80, $height = 30, $length = 4)
    {
        //生成随机码
        $this->generateCode($length);

        //创建画板
        $this->createImage($width, $height);

        //设置干扰元素
        $this->setDisturbColor($width, $height, $length);

        //向图像中随机画出文本
        $this->outputText($width, $height, $length);
    }

    /**
     * 显示图片
     */
    public function show()
    {
        //输出图像
        $this->outputImage();
    }


    /**
     * 生成随机码
     *
     * @param int $length
     * @return string
     */
    protected function generateCode($length)
    {
        $this->codeStr = Str::randNumStr($length);
    }

    /**
     * 创建画板
     *
     * @param $width
     * @param $height
     */
    protected function createImage($width, $height)
    {
        //创建图像资源
        $this->image = imagecreatetruecolor($width, $height);
        //随机背景色
        $backColor = imagecolorallocate($this->image, rand(210, 250), rand(210, 250), rand(210, 250));
        //为背景添充颜色
        imagefill($this->image, 0, 0, $backColor);
        //设置边框颜色
        $border = imagecolorallocate($this->image, 0, 0, 0);
        //画出矩形边框
        imagerectangle($this->image, 0, 0, $width - 1, $height - 1, $border);
    }

    /**
     * 设置干扰元素
     *
     * @param $width
     * @param $height
     * @param $length
     */
    protected function setDisturbColor($width, $height, $length)
    {
        $number = floor($width * $height / 15);
        if ($number > 240 - $length) {
            $this->disturbColorNum = 240 - $length;
        } else {
            $this->disturbColorNum = $number;
        }

        for ($i = 0; $i < $this->disturbColorNum; $i++) {
            $color = imagecolorallocate($this->image, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($this->image, rand(1, $width - 2), rand(1, $height - 2), $color);
        }
        for ($i = 0; $i < 10; $i++) {
            $color = imagecolorallocate($this->image, rand(200, 255), rand(200, 255), rand(200, 255));
            imagearc($this->image, rand(-10, $width), rand(-10, $height), rand(30, 300), rand(20, 200), 55, 44, $color);
        }
    }

    /**
     * 输出文本
     */
    protected function outputText($width, $height, $length)
    {
        for ($i = 0; $i < $length; $i++) {
            $fontcolor = imagecolorallocate($this->image, rand(0, 128), rand(0, 128), rand(0, 128));

            $fontsize = rand(13, 18);
            $x = floor($width / $length) * $i + 3;
            $y = rand(0, $height - 15);
            imagechar($this->image, $fontsize, $x, $y, $this->codeStr{$i}, $fontcolor);
        }
    }

    /**
     * 输出图片
     */
    protected function outputImage()
    {
        header("Content-Type:image/png");
        imagepng($this->image);
    }

    /**
     * 返回验证码的值
     *
     * @return string
     */
    public function getCode()
    {
        return $this->codeStr;
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }
}