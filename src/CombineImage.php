<?php

declare (strict_types=1);

namespace Hcg\CombineImage;

/**
 * 拼接多幅图片成为一张图片
 */
class CombineImage
{
    /**
     * 原图地址数组
     */
    private $srcImages = [];
    /**
     * 每张图片缩放到这个宽度
     */
    private $width;
    /**
     * 每张图片缩放到这个高度
     */
    private $height;

    /**
     * 每行的图片数量
     */
    private $eachLineCount;

    /**
     * 每列的图片数量
     */
    private $eachColumCount;
    /**
     * 拼接模式，可以选择水平或垂直
     */
    private $mode;
    /**
     * 水平拼接模式常量
     */
    const COMBINE_MODE_HORIZONTAL = "horizontal";
    /**
     * 垂直拼接模式常量
     */
    const COMBINE_MODE_VERTICAL = "vertical";
    /**
     * 目标图片地址
     */
    private $destImage;
    /**
     * 临时画布
     */
    private $canvas;
    /**
     * 临时画布的背景颜色（默认值为白色）
     */
    private $canvasBgColor = [
        'red' => 255,
        'green' => 255,
        'blue' => 255,
    ];
    /**
     * 背景色是否为透明
     */
    private $transparent = true;

    /**
     * MergeImage constructor.
     * @param string $srcImages
     * @param string $desImage
     * @param int $width
     * @param int $height
     *
     */
    public function __construct($srcImages = '', $desImage = '', $width = 200, $height = 200)
    {
        $this->srcImages = $srcImages;
        $this->destImage = $desImage;
        $this->width = $width;
        $this->height = $height;
        $this->mode = self::COMBINE_MODE_VERTICAL;
        $this->canvas = NULL;
    }

    public function __destruct()
    {
        if ($this->canvas != NULL) {
            imagedestroy($this->canvas);
        }
    }

    /**
     * 合并图片
     */
    public function combine()
    {
        if (empty($this->srcImages) || $this->width == 0 || $this->height == 0) {
            return;
        }
        $imageCount = count($this->srcImages);
        $this->prepareForCanvas($imageCount);
        for ($i = 0; $i < $imageCount; $i++) {
            $srcImage = $this->srcImages[$i];
            $srcImageInfo = getimagesize($srcImage);
            // 如果能够正确的获取原图的基本信息
            if ($srcImageInfo) {
                $srcWidth = $srcImageInfo[0];
                $srcHeight = $srcImageInfo[1];
                $fileType = $srcImageInfo[2];
                if ($fileType == 2) {
                    // 原图是 jpg 类型
                    $srcImage = imagecreatefromjpeg($srcImage);
                } else if ($fileType == 3) {
                    // 原图是 png 类型
                    $srcImage = imagecreatefrompng($srcImage);
                } else {
                    // 无法识别的类型
                    continue;
                }

                //只支持横向
                // 计算当前原图片应该位于画布的哪个位置
                if ($i < $this->eachLineCount) {
                    $destX = $i * $this->width;
                    $desyY = 0;
                    $currentRowIndex = 0;
                } else {
                    //计算该图片应该在第几行， 第几列
                    $tmp = ($i + 1) / $this->eachLineCount;
                    if (($i + 1) % $this->eachLineCount == 0) {
                        $currentRowIndex = $tmp - 1;
                    } else {
                        $currentRowIndex = floor($tmp);
                    }

                    $destX = ($i - $currentRowIndex * $this->eachLineCount) * $this->width;
                    $desyY = $currentRowIndex * $this->height;
                }

                imagecopyresampled(
                    $this->canvas,
                    $srcImage, intval(ceil($destX)),
                    intval(ceil($desyY)),
                    0, 0,
                    intval(ceil($this->width)),
                    intval(ceil($this->height)),
                    intval(ceil($srcWidth)),
                    intval(ceil($srcHeight))
                );
            }
        }

        // 如果有指定目标地址，则输出到文件
        if (!empty($this->destImage)) {
            $this->output();
        }
    }

    /**
     * 输出结果到浏览器
     */
    public function show()
    {
        if ($this->canvas == NULL) {
            return;
        }
        header("Content-type: image/jpeg");
        imagejpeg($this->canvas);
    }

    /**
     * 根据图片数据计算画布的大小
     * 默认每张图宽度高度 = 200
     * 画布大小计算方法：
     * 列数 = 总数（大于3）的平方根， 向上取整
     * 行数 = 总数（大于3）除以列数， 向上取整
     * @param $imageCount
     */
    private function prepareForCanvas($imageCount)
    {
        $canvasWidth = 0;
        $canvasHeight = 0;
        if ($imageCount > 0) {
            switch ($this->mode){
                case self::COMBINE_MODE_VERTICAL:
                    // 垂直拼接模式
                    $this->eachLineCount = 1;
                    $this->eachColumCount = $imageCount;
                    $canvasWidth = $this->width * $this->eachLineCount;
                    $canvasHeight = $this->height * $this->eachColumCount;
                    break;
                case self::COMBINE_MODE_HORIZONTAL:
                    // 水平拼接模式
                    if ($imageCount < 4) {
                        $canvasWidth = $this->width * $imageCount;
                        $this->eachLineCount = $imageCount;
                        $canvasHeight = $this->height;
                    } else {
                        $this->eachLineCount = ceil(sqrt($imageCount)); //列数
                        $this->eachColumCount = ceil($imageCount / $this->eachLineCount);
                        $canvasWidth = $this->width * $this->eachLineCount;
                        $canvasHeight = $this->height * $this->eachColumCount;
                    }
                    break;
            }
        }

        //创建画布
        $this->createCanvas($canvasWidth, $canvasHeight);
    }

    /**
     * 创建画布
     * @param $cwidth
     * @param $cheight
     */
    private function createCanvas($cwidth, $cheight)
    {
        $totalImage = count($this->srcImages);
        if ($this->mode == self::COMBINE_MODE_HORIZONTAL) {
            $width = $totalImage * $this->width;
            $height = $this->height;
        } else if ($this->mode == self::COMBINE_MODE_VERTICAL) {
            $width = $this->width;
            $height = $totalImage * $this->height;
        }
        $this->canvas = imagecreatetruecolor(intval(ceil($cwidth)), intval(ceil($cheight)));

        // 使画布透明
        $white = imagecolorallocate($this->canvas, $this->canvasBgColor['red'], $this->canvasBgColor['green'], $this->canvasBgColor['blue']);
        imagefill($this->canvas, 0, 0, $white);

        if($this->transparent){
            imagecolortransparent($this->canvas, $white);
        }
    }

    /**
     * 私有函数，保存结果到文件
     */
    private function output()
    {
        // 获取目标文件的后缀
        $fileType = substr(strrchr($this->destImage, '.'), 1);
        if ($fileType == 'jpg' || $fileType == 'jpeg') {
            imagejpeg($this->canvas, $this->destImage);
        } else {
            // 默认输出 png 图片
            imagepng($this->canvas, $this->destImage);
        }

    }

    /**
     * @return  String $srcImages
     */
    public function getSrcImages(): string
    {
        return $this->srcImages;
    }

    /**
     * @param String $srcImages
     * @return CombineImage
     */
    public function setSrcImages(string $srcImages): CombineImage
    {
        $this->srcImages = $srcImages;
        return $this;
    }

    /**
     * @return int $width
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return CombineImage
     */
    public function setWidth(int $width): CombineImage
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int $height
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return CombineImage
     */
    public function setHeight(int $height): CombineImage
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string $mode
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * @param $mode
     * @return CombineImage
     */
    public function setMode($mode): CombineImage
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @return string $destImage
     */
    public function getDestImage(): string
    {
        return $this->destImage;
    }

    /**
     * @param String $destImage
     * @return CombineImage
     */
    public function setDestImage(string $destImage): CombineImage
    {
        $this->destImage = $destImage;
        return $this;
    }

    /**
     * 设置Canvas画布背景颜色RGB值
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return CombineImage
     */
    public function setCanvasBgColor(int $red, int $green, int $blue): CombineImage
    {
        $this->canvasBgColor = [
            'red' => $red,
            'green' => $green,
            'blue' => $blue,
        ];
        return $this;
    }

    /**
     * 设置背景是否透明
     *
     * @param bool $transparent
     * @return CombineImage
     */
    public function setTransparent(bool $transparent): CombineImage
    {
        $this->transparent = $transparent;
        return $this;
    }
}