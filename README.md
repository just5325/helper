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

### 常用数组操作
#### 将二维数组变成指定key的三维数组
```
$arr1 = [
    ['id' => 1, 'type' => 1, 'name' => '黄翠刚'],
    ['id' => 2, 'type' => 1, 'name' => '黄翠刚'],
    ['id' => 3, 'type' => 2, 'name' => '黄翠刚'],
    ['id' => 4, 'type' => 2, 'name' => '黄翠刚'],
    ['id' => 5, 'type' => 2, 'name' => '黄翠刚'],
    ['id' => 6, 'type' => 4, 'name' => '黄翠刚'],
];
$arr2 = HelperArray::buildArrByGroupKey($arr1, 'type');
```
$arr2打印结果：
```
array(3) {
  [1]=>
  array(2) {
    [0]=>
    array(3) {
      ["id"]=>
      int(1)
      ["type"]=>
      int(1)
      ["name"]=>
      string(9) "黄翠刚"
    }
    [1]=>
    array(3) {
      ["id"]=>
      int(2)
      ["type"]=>
      int(1)
      ["name"]=>
      string(9) "黄翠刚"
    }
  }
  [2]=>
  array(3) {
    [0]=>
    array(3) {
      ["id"]=>
      int(3)
      ["type"]=>
      int(2)
      ["name"]=>
      string(9) "黄翠刚"
    }
    [1]=>
    array(3) {
      ["id"]=>
      int(4)
      ["type"]=>
      int(2)
      ["name"]=>
      string(9) "黄翠刚"
    }
    [2]=>
    array(3) {
      ["id"]=>
      int(5)
      ["type"]=>
      int(2)
      ["name"]=>
      string(9) "黄翠刚"
    }
  }
  [4]=>
  array(1) {
    [0]=>
    array(3) {
      ["id"]=>
      int(6)
      ["type"]=>
      int(4)
      ["name"]=>
      string(9) "黄翠刚"
    }
  }
}
```

#### 将二维数组变成指定key
```
$arr3 = HelperArray::buildArrByNewKey($arr1, 'id');
```
$arr3打印结果：
```
array(6) {
  [1]=>
  array(3) {
    ["id"]=>
    int(1)
    ["type"]=>
    int(1)
    ["name"]=>
    string(9) "黄翠刚"
  }
  [2]=>
  array(3) {
    ["id"]=>
    int(2)
    ["type"]=>
    int(1)
    ["name"]=>
    string(9) "黄翠刚"
  }
  [3]=>
  array(3) {
    ["id"]=>
    int(3)
    ["type"]=>
    int(2)
    ["name"]=>
    string(9) "黄翠刚"
  }
  [4]=>
  array(3) {
    ["id"]=>
    int(4)
    ["type"]=>
    int(2)
    ["name"]=>
    string(9) "黄翠刚"
  }
  [5]=>
  array(3) {
    ["id"]=>
    int(5)
    ["type"]=>
    int(2)
    ["name"]=>
    string(9) "黄翠刚"
  }
  [6]=>
  array(3) {
    ["id"]=>
    int(6)
    ["type"]=>
    int(4)
    ["name"]=>
    string(9) "黄翠刚"
  }
}
```
