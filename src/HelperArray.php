<?php

declare (strict_types=1);

namespace Hcg\HelperArray;

/**
 * 数组助手类
 *
 * @author hcg
 * @email 532508307@qq.com
 */
class HelperArray
{
    /**
     * 二维数组根据多个字段排序
     * @param array $array 要排序的数组
     * @param array $keys 要排序的键字段(数组,分清楚排序字段的先后顺序)
     * @param int $sort 排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     * @author hcg<532508307@qq.com>
     */
    public static function arraySortMultipleKey(array $array, array $keys, int $sort = SORT_DESC): array
    {
        if (!empty($array) && !empty($keys)) {
            $keys_ = [];
            $fun_str = 'array_multisort(';
            foreach ($keys as $key1 => $value1) {
                $k1 = (is_array($value1) && isset($value1[0]) ? $value1[0] : $value1);
                $v1 = (is_array($value1) && isset($value1[1]) ? $value1[1] : $sort);
                $fun_str .= '$keys_[\'' . $k1 . '\'],' . $v1 . ',';
                foreach ($array as $key2 => $value2) {
                    $keys_[$k1][$key2] = $value2[$k1];
                }
            }
            $fun_str .= '$array);';
            eval($fun_str);
        }
        return $array;
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param int|string $keys 要排序的键字段
     * @param int $sort 排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     * @author hcg<532508307@qq.com>
     */
    public static function arraySort(array $array, $keys, int $sort = SORT_DESC): array
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 将二维数组变成指定key
     * @param array $array
     * @param string $keyName
     * @return array
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public static function buildArrByNewKey(array $array, string $keyName = 'id'): array {
        $list = [];
        foreach ($array as $item) {
            $list[$item[$keyName]] = $item;
        }

        return $list;
    }

    /**
     * 将二维数组变成指定key的三维数组
     * @param array $array
     * @param string $keyName
     * @return array
     * @author hcg<532508307@qq.com>
     */
    public static function buildArrByGroupKey(array $array, string $keyName): array {
        if(empty($array)){
            return [];
        }
        $list = [];
        foreach ($array as $value){
            $list[$value[$keyName]] = $list[$value[$keyName]] ?? [];
            $list[$value[$keyName]][] = $value;
        }
        return $list;
    }

    /**
     * 将数据库查询的结果集转换成数组
     * 注意，这个方法是某些支持toArray方法的框架才可以使用
     * @param $data
     * @return array
     * @author hcg<532508307@qq.com>
     */
    public static function toArray($data): array
    {
        if($data && !is_array($data)) {
            $data = $data->toArray();
        }
        return is_array($data) ? $data : [];
    }
}
