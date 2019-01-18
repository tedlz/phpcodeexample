<?php

namespace App\Library;

/**
 * 普通等级
 * @author lz
 */
class GeneralLevel
{
    /**
     * 用户等级
     * @var exp 升到当前等级所需经验值
     * @var total 当前等级总经验值
     * @var limit 当前等级每日限制获取经验上限
     */
    private static $levelInfo = [
        1 => ['exp' => 0, 'total' => 0],
        2 => ['exp' => 80, 'total' => 80],
        3 => ['exp' => 140, 'total' => 220],
        4 => ['exp' => 220, 'total' => 440],
        5 => ['exp' => 320, 'total' => 760],
        6 => ['exp' => 440, 'total' => 1200],
        7 => ['exp' => 580, 'total' => 1780],
        8 => ['exp' => 740, 'total' => 2520],
        9 => ['exp' => 920, 'total' => 3440],
        10 => ['exp' => 1120, 'total' => 4560],
        11 => ['exp' => 1340, 'total' => 5900],
        12 => ['exp' => 1580, 'total' => 7480],
        13 => ['exp' => 1840, 'total' => 9320],
        14 => ['exp' => 2120, 'total' => 11440],
        15 => ['exp' => 2420, 'total' => 13860],
        16 => ['exp' => 2740, 'total' => 16600],
        17 => ['exp' => 3080, 'total' => 19680],
        18 => ['exp' => 3440, 'total' => 23120],
        19 => ['exp' => 3820, 'total' => 26940],
        20 => ['exp' => 4220, 'total' => 31160],
        21 => ['exp' => 4640, 'total' => 35800],
        22 => ['exp' => 5080, 'total' => 40880],
        23 => ['exp' => 5540, 'total' => 46420],
        24 => ['exp' => 6020, 'total' => 52440],
        25 => ['exp' => 6520, 'total' => 58960],
        26 => ['exp' => 7040, 'total' => 66000],
        27 => ['exp' => 7580, 'total' => 73580],
        28 => ['exp' => 8140, 'total' => 81720],
        29 => ['exp' => 8720, 'total' => 90440],
        30 => ['exp' => 9320, 'total' => 99760],
        31 => ['exp' => 9940, 'total' => 109700],
        32 => ['exp' => 10580, 'total' => 120280],
        33 => ['exp' => 11240, 'total' => 131520],
        34 => ['exp' => 11920, 'total' => 143440],
        35 => ['exp' => 12620, 'total' => 156060],
        36 => ['exp' => 13340, 'total' => 169400],
        37 => ['exp' => 14080, 'total' => 183480],
        38 => ['exp' => 14840, 'total' => 198320],
        39 => ['exp' => 15620, 'total' => 213940],
        40 => ['exp' => 16420, 'total' => 230360],
        41 => ['exp' => 17240, 'total' => 247600],
        42 => ['exp' => 18080, 'total' => 265680],
        43 => ['exp' => 18940, 'total' => 284620],
        44 => ['exp' => 19820, 'total' => 304440],
        45 => ['exp' => 20720, 'total' => 325160],
        46 => ['exp' => 21640, 'total' => 346800],
        47 => ['exp' => 22580, 'total' => 369380],
        48 => ['exp' => 23540, 'total' => 392920],
        49 => ['exp' => 24520, 'total' => 417440],
        50 => ['exp' => 25520, 'total' => 442960],
        51 => ['exp' => 26540, 'total' => 469500],
        52 => ['exp' => 27580, 'total' => 497080],
        53 => ['exp' => 28640, 'total' => 525720],
        54 => ['exp' => 29720, 'total' => 555440],
        55 => ['exp' => 30820, 'total' => 586260],
        56 => ['exp' => 31940, 'total' => 618200],
        57 => ['exp' => 33080, 'total' => 651280],
        58 => ['exp' => 34240, 'total' => 685520],
        59 => ['exp' => 35420, 'total' => 720940],
        60 => ['exp' => 36620, 'total' => 757560],
        61 => ['exp' => 37840, 'total' => 795400],
        62 => ['exp' => 39080, 'total' => 834480],
        63 => ['exp' => 40340, 'total' => 874820],
        64 => ['exp' => 41620, 'total' => 916440],
        65 => ['exp' => 42920, 'total' => 959360],
        66 => ['exp' => 44240, 'total' => 1003600],
        67 => ['exp' => 45580, 'total' => 1049180],
        68 => ['exp' => 46940, 'total' => 1096120],
        69 => ['exp' => 48320, 'total' => 1144440],
        70 => ['exp' => 49720, 'total' => 1194160],
        71 => ['exp' => 51140, 'total' => 1245300],
        72 => ['exp' => 52580, 'total' => 1297880],
        73 => ['exp' => 54040, 'total' => 1351920],
        74 => ['exp' => 55520, 'total' => 1407440],
        75 => ['exp' => 57020, 'total' => 1464460],
        76 => ['exp' => 58540, 'total' => 1523000],
        77 => ['exp' => 60080, 'total' => 1583080],
        78 => ['exp' => 61640, 'total' => 1644720],
        79 => ['exp' => 63220, 'total' => 1707940],
        80 => ['exp' => 64820, 'total' => 1772760],
        81 => ['exp' => 66440, 'total' => 1839200],
        82 => ['exp' => 68080, 'total' => 1907280],
        83 => ['exp' => 69740, 'total' => 1977020],
        84 => ['exp' => 71420, 'total' => 2048440],
        85 => ['exp' => 73120, 'total' => 2121560],
        86 => ['exp' => 74840, 'total' => 2196400],
        87 => ['exp' => 76580, 'total' => 2272980],
        88 => ['exp' => 78340, 'total' => 2351320],
        89 => ['exp' => 80120, 'total' => 2431440],
        90 => ['exp' => 81920, 'total' => 2513360],
        91 => ['exp' => 83740, 'total' => 2597100],
        92 => ['exp' => 85580, 'total' => 2682680],
        93 => ['exp' => 87440, 'total' => 2770120],
        94 => ['exp' => 89320, 'total' => 2859440],
        95 => ['exp' => 91220, 'total' => 2950660],
        96 => ['exp' => 93140, 'total' => 3043800],
        97 => ['exp' => 95080, 'total' => 3138880],
        98 => ['exp' => 97040, 'total' => 3235920],
        99 => ['exp' => 99020, 'total' => 3334940],
        100 => ['exp' => 101020, 'total' => 3435960],
    ];

    /**
     * 获取等级
     * @param  int $exp 当前用户的经验值
     * @return int $level 等级
     */
    public static function getLevel($exp)
    {
        $exp = intval($exp);
        if ($exp <= 0) {
            return 1;
        }

        $total = array_column(self::$levelInfo, 'total'); //先取出 levelInfo 中 total 总经验值字段，作为新数组
        $total[] = $exp; //把用户经验加进来
        sort($total); //正序排列
        $flip = array_flip($total); //把该数组的键值反转，从 level => total 变成 total => level

        $level = $flip[$exp]; //这样用户经验所在的等级就是当前等级

        //如果大于等级上限，返回上限
        $upperLimit = count($total) - 1;
        if ($level > $upperLimit) {
            $level = $upperLimit;
        }

        return $level;
    }

    //获取下一级升级经验
    public static function getRemainder($level, $exp)
    {
        $remainder = isset(self::$levelInfo[$level + 1]) ? self::$levelInfo[$level + 1]['total'] - $exp : 0;

        return $remainder;
    }

    //获取当前等级总的经验值
    public static function getTotal($level)
    {
        return self::$levelInfo[$level]['total'];
    }
}
