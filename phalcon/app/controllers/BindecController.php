<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;

class BindecController extends Controller
{
    public function testAction()
    {
        var_dump(
            $this->_powerFor2(33),
            $this->_getNumbers(16, array_sum([1, 2, 4, 8]))
        );
    }

    /**
     * 计算一个数中所包含的 2 的 n 次方
     * @example
     *     $decimal = 15 返回 [1, 2, 4, 8]
     *     $decimal = 33 返回 [1, 32]
     */
    private function _powerFor2($decimal)
    {
        $bin = decbin($decimal);
        $total = strlen($bin);

        $stock = [];
        for ($i = $total - 1; $i >= 0; $i--) {
            if ($bin[$i]) {
                $stock[] = bindec(str_pad($bin[$i], $total - $i, 0));
            }
        }

        return $stock;
    }

    /**
     * 给出 2 的 n 次方，例如 $value = 1 2 4 8，返回包含 $value 的数
     * 例如给出
     * @param $value 例如 PC=1，iPhone=2，iPad=4，Android=8
     * @param $sum 数的和，例如 $terminal 的和是 1+2+4+8=15
     * @return 包含 $value 的数组
     * @example
     *     value = 8
     *     sum = array_sum([
     *         1, // PC
     *         2, // iPhone
     *         4, // iPad
     *         8, // Android
     *         ]) = 15
     *     result = [
     *         8, // 8 = 2的3次方
     *         9, // 8+1 = 2的3次方 + 2的0次方
     *         10, // 8+2 = 2的3次方 + 2的1次方
     *         11, // 8+2+1 = 2的3次方 + 2的1次方 + 2的0次方
     *         12, // 8+4 = 2的3次方 + 2的2次方
     *         13, // 8+4+1
     *         14, // 8+4+2
     *         15, // 8+4+2+1
     *     ]
     */
    private function _getNumbers($value, $sum)
    {
        $result = [];

        // 遍历 sum
        for ($i = 1; $i <= $sum; $i++) {
            $bindec = $this->_powerFor2($i); // 得到 $i 包含的 2 的 n 次方
            // 如果 $value 在返回的数组中
            if (in_array($value, $bindec)) {
                $result[] = array_sum($bindec);
            }
        }

        return $result;
    }
}
