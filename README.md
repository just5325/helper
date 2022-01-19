# helper
一些常用的助手函数集合

### 安装
```
composer require hcg/helper
```

### 拼接图片
```
// 这是需要拼接的图片数组
$images = ['图片1路径','图片2路径','图片3路径'];
// 这是合成后的图片保存地址
$combine_image = '/tmp/1.png';
// 每张图片的宽高
$width = 200;
$height = 200;
$ci = new CombineImage($images, $combine_image, $width, $height);
$ci->setTransparent(false)->combine();
```
