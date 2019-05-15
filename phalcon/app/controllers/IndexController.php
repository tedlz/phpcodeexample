<?php

namespace App\Controllers;

use App\Library\Http;
use App\Library\STD3Des;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    const MATCH_SEMIFINAL = '半决赛';
    const MATCH_FINAL = '决赛';
    const GROUP_WIN = '胜者组';
    const GROUP_LOSE = '败者组';

    public function _test($date)
    {
        $number = 16;
        $n = log($number, 2);
        // count(data[胜者组]) = log₂N + 1 = n + 1
        $data = [
            self::GROUP_WIN => [],
            self::GROUP_LOSE => [],
        ];
        for ($i = 1; $i <= $n - 1; $i++) {
            $data[self::GROUP_WIN][] = sprintf('第 %d 轮', $i);
        }
        $data[self::GROUP_WIN][] = self::MATCH_SEMIFINAL;
        $data[self::GROUP_WIN][] = self::MATCH_FINAL;

        // count(data[败者组]) = 2log₂N - 2 = 2n - 2
        $a = $n * 2 - 2;
        for ($i = 1; $i <= $a; $i++) {
            $data[self::GROUP_LOSE][] = sprintf('第 %d 轮', $i);
        }

        $weight = 1; // 父级目录权重
        // 胜者组和败者组分别计算
        $numberWin = $number; // 胜者组第一轮人数
        $numberLose = $number / 2; // 败者组第一轮人数
        $lose = []; // 失败者人数
        $result = [];

        // 记录每轮有几场
        $roundWin = []; // 记录胜者组每轮有几场
        $roundLose = []; // 计算败者组每轮有几场
        foreach ($data as $k => $v) {
            $countV = count($v);
            foreach ($v as $kk => $vv) {
                if ($k == self::GROUP_WIN) {
                    // 如果是胜者组，计算胜者组每轮的场次
                    $count = ceil($numberWin / 2);
                    $numberWin -= $count;

                    // 当非首轮（第一轮）和决赛轮（最后一轮）时，记录胜者组被淘汰的人数
                    if ($kk > 0 && $kk < $countV - 1) {
                        $lose[] = $count; // 胜者组每轮被淘汰和胜利的人数一样多
                    }

                    $roundWin[] = $count;
                    // echo self::GROUP_WIN, ' ', $count, PHP_EOL;
                } else if ($k == self::GROUP_LOSE) {
                    // 偶数轮且非最后一轮时，把胜者组淘汰的选手加到败者组里，再来计算败者组场次
                    if ($kk % 2 == 1 && $kk < $countV - 1) {
                        $numberLose += array_shift($lose);
                    }

                    // 如果是败者组，计算败者组每轮的场次
                    $count = ceil($numberLose / 2);
                    $numberLose -= $count;

                    $roundLose[] = $count;
                    // echo self::GROUP_LOSE, ' ', $count, PHP_EOL;
                }
            }
        }

        // 记录场次编号
        $indexWin = []; // 记录胜者组每轮的编号
        $indexLose = []; // 记录败者组每轮的编号
        $index = 1;
        for ($i = 1; $i <= $n + 1; $i++) {
            $Wi = $i - 1;
            $L1i = ($i - 1) * 2 - 1;
            $L2i = ($i - 1) * 2;
            $W = $roundWin[$Wi];
            $L1 = isset($roundLose[$L1i]) ? $roundLose[$L1i] : 0;
            $L2 = isset($roundLose[$L2i]) ? $roundLose[$L2i] : 0;

            for ($j = 0; $j < $W; $j++) {
                $indexWin[$Wi] = $index;
                $index++;
            }
            for ($k = 0; $k < $L1; $k++) {
                $indexLose[$L1i] = $index;
                $index++;
            }
            for ($l = 0; $l < $L2; $l++) {
                $indexLose[$L2i] = $index;
                $index++;
            }
        }
        $indexWin[count($indexWin) - 1] = $index - 1;
        print_r($roundWin);
        print_r($indexWin);
        print_r($roundLose);
        print_r($indexLose);
        // die;
        $sort = [];
        $result = [];
        foreach ($data as $k => $v) {
            foreach ($v as $kk => $vv) {
                if ($k == self::GROUP_WIN) {
                    // 胜者组
                    for ($i = 1; $i <= $roundWin[$kk]; $i++) {
                        $round = $indexWin[$kk] - $roundWin[$kk] + $i;
                        $str = sprintf('%s_%s_第 %s 场', $k, $vv, $round);
                        $sort[] = $round;
                        $result[] = $str;
                    }
                } else if ($k == self::GROUP_LOSE) {
                    // 败者组
                    for ($i = 1; $i <= $roundLose[$kk]; $i++) {
                        $round = $indexLose[$kk] - $roundLose[$kk] + $i;
                        $str = sprintf('%s_%s_第 %s 场', $k, $vv, $round);
                        $sort[] = $round;
                        $result[] = $str;
                    }
                }
            }
        }
        array_multisort($sort, SORT_ASC, $result);
        print_r($result);
        die;
        $arr = range('A', 'Z');
        // shuffle($arr);
        // print_r($arr);
        $number = count($arr);
        $groupNumber = 3;
        $result = [];
        for ($i = 0; $i < ceil($number / $groupNumber); $i++) {
            $tmp = [];
            for ($j = 0; $j < $groupNumber; $j++) {
                $tmp[] = $arr[$i * $groupNumber + $j];
            }
            $result[] = $tmp;
        }
        print_r($result);
        die;

        $arr = [
            [
                'id' => 1,
                'name' => 'A',
            ],
            [
                'id' => 2,
                'name' => 'B',
                'children' => [
                    [
                        'id' => 22,
                        'name' => 'BB',
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'C',
                'children' => [
                    [
                        'id' => 33,
                        'name' => 'CC',
                        'children' => [
                            [
                                'id' => 333,
                                'name' => 'CCC',
                            ],
                        ],
                    ],
                ],
            ],
        ];
        print_r($this->abc($arr));

        die;
        $x = 128;
        $n = log($x, 2);
        $data = [
            '胜者组' => [],
            '败者组' => [],
        ];
        for ($i = 1; $i <= $n - 2; $i++) {
            $data['胜者组'][] = sprintf('第 %d 轮', $i);
        }
        $data['胜者组'][] = '半决赛';
        $data['胜者组'][] = '决赛';
        print_r($data['胜者组']);
        echo $n, ' -- ', count($data['胜者组']), PHP_EOL, PHP_EOL;

        $a = ($n * 2) - ($n % 2 + 1);
        for ($i = 1; $i <= $a; $i++) {
            $data['败者组'][] = sprintf('第 %d 轮', $i);
        }
        print_r($data['败者组']);
        echo $a;

        echo PHP_EOL;
        die;
        $key = 'h01dfa5t&291iveMatch';
        $time = time();
        $sign = md5(md5($time . $key) . $key . md5($key . $time));
        print_r([
            'time' => $time,
            'sign' => $sign,
        ]);

        die;
        // $s = microtime(true);
        // $start = '2019-02-15 00:00:00';
        // $end = '2019-08-25 23:59:59';
        // $range = [
        //     ['start' => '2019-02-15', 'end' => '2019-03-18'],
        //     ['start' => '2019-04-05', 'end' => '2019-04-22'],
        //     ['start' => '2019-04-27', 'end' => '2019-04-28'],
        //     ['start' => '2019-05-03', 'end' => '2019-05-06'],
        //     ['start' => '2019-06-07', 'end' => '2019-07-01'],
        //     ['start' => '2019-07-06', 'end' => '2019-07-07'],
        //     ['start' => '2019-07-26', 'end' => '2019-08-19'],
        //     ['start' => '2019-08-24', 'end' => '2019-08-25'],
        // ];
        // for ($i = strtotime($start); $i < strtotime($end); $i += 86400) {
        //     $N = date('N');
        //     if (in_array($N, [2, 3, 4])) {
        //         continue;
        //     }

        //     foreach ($range as $v) {
        //         $d = date('Y-m-d', $i);
        //         if ($d >= $v['start'] && $d <= $v['end']) {
        //             echo $d, PHP_EOL;
        //             break;
        //         }
        //     }
        // }
        // $e = microtime(true);
        // echo sprintf('执行时间：%.4f', $e - $s);

        // die;
        $a = sprintf("%u", crc32('天枢平台'));
        $b = hash_hmac('ripemd160', '天枢平台', $a);
        print_r([
            $a, $b,
        ]);
    }

    /**
     *
     */
    private function _ws()
    {
        $appKey = 'uA8iPtvDoJpz';
        $t = gmdate('D, d M Y H:i:s') . ' GMT';
        $username = 'bianfengshipin';
        $password = base64_encode(hash_hmac('SHA1', $t, $appKey, true));
        $header = [
            'Authorization: Basic ' . base64_encode($username . ':' . $password),
            'Accept: application/xml',
            'Date: ' . $t,
        ];

        $start = '2018-12-17T23:55:00%2B08:00';
        $end = '2018-12-18T00:59:59%2B08:00';
        // print_r([
        //     'datefrom' => $start,
        //     'dateto' => $end,
        // ]);
        $url = 'https://open.chinanetcenter.com/api/report/domainbandwidth';
        $url .= '?dateFrom=' . $start . '&dateTo=' . $end . '&type=fiveminutes';
        $param = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<domain-list>'
            . '<domain-name>wshdl.cdn.zhanqi.tv</domain-name>'
            . '<domain-name>wshdl.load.cdn.zhanqi.tv</domain-name>'
            . '<domain-name>wshls.cdn.zhanqi.tv</domain-name>'
            . '</domain-list>';
        // $param = json_encode([
        //     'domain-list' => [
        //         'wshdl.cdn.zhanqi.tv',
        //         'wshdl.load.cdn.zhanqi.tv',
        //         'wshls.cdn.zhanqi.tv',
        //     ],
        // ]);

        $data = Http::post($url, $param, $header);
        if (empty($data)) {
            exit('无数据' . PHP_EOL);
        }

        $data = str_replace('&', '{xml}', $data);
        $xmlstring = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        $arr = str_replace('{xml}', '&', $val);

        if (!isset($arr['bandwidth-data'])) {
            exit('数据解析失败' . PHP_EOL);
        }

        $bandwidth = $arr['bandwidth-data'];
        foreach ($bandwidth as $v) {
            print_r($v);
        }
    }

    public function initialize()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit;
        }

        // $this->_ws();
        // die;

        $this->_test();
        die;

        $key = '4A94B20C269244748B9B31C159FADCAD';
        $iv = '01234567';

        $mobile = '17326056026';
        $encode = STD3Des::encrypt($mobile, $key, $iv);
        echo STD3Des::decrypt($encode, $key, $iv), PHP_EOL;
        echo $encode, PHP_EOL;
        echo 'If8JBLc85ErwuRDDWIeueA==', PHP_EOL;
        $code = '4826';
        $deviceId = 'abcdefg';
        $signature = sprintf(
            'telephone=%s&verifyCode=%s&cusid=%s',
            $encode,
            $code,
            $deviceId
        );
        echo $signature, PHP_EOL;
        echo hash('sha256', $signature), PHP_EOL;
        echo '178cf3f032d24828b8f1c70fed6e91c8faf67d57aa66a789be89ba7b84a326d5';
        die;

        $cdn = [];
        $config = [
            [
                'cdn' => [172, 202, 42, 12],
                'rate' => [25, 25, 25, 25],
            ],
            [
                'cdn' => [172, 202, 42, 12],
                'rate' => [25, 25, 25, 0],
            ],
            [
                'cdn' => [172, 202, 42, 12],
                'rate' => [10, 20, 30, 40],
            ],
            [
                'cdn' => [172, 0, 0, 0],
                'rate' => [100, 0, 0, 0],
            ],
            [
                'cdn' => [172, 202, 0, 12],
                'rate' => [20, 20, 20, 200],
            ],
            [
                'cdn' => [172, 202, 42, 12],
                'rate' => [15, 15, 100, 15],
            ],
        ];

        // 循环处理码率
        foreach ($config as $conf) {
            $tempCdn = ''; // 码率使用的线路
            $rand = mt_rand(1, 100); // 生成 1-100 之间的随机数，假如随机数是 75

            // 循环处理概率
            $sum = []; // 概率的和数组
            foreach ($conf['rate'] as $i => $rate) {
                $sum[] = $rate; // 每循环一次，把循环过的概率加进数组
                $_sum = array_sum($sum); // 计算循环过的概率的和
                if ($rand <= $_sum) { // 如果是 4 个 25%，那么这里循环判断就是 75 <= 25、75 <= 50、75 <= 75
                    $tempCdn = $conf['cdn'][$i]; // 此时 i = 2，取到第三条线路
                    break; // 取到线路后跳出
                }
            }

            // 如果未取到线路，例如概率四个相加不足 100% 或者超过 100%
            if (!$tempCdn) {
                // 处理方式 1：取概率最大的线路出来
                $i = array_search(max($conf['rate']), $conf['rate']); // 取数组中概率最大的（max()）值的索引（$i）
                $tempCdn = $conf['cdn'][$i]; // 把线路中概率最大的拿出来

                // 处理方式 2：随机取出来
                // $filter = array_filter($conf['rate']); // 假如是 [172, '', 0, 202]，那么会过滤为 [172, 202]
                // $i = array_rand($filter); // 此时随机从数组取，$i 的值只能为 0 或 1
                // $tempCdn = $filter[$i]; // 取得线路
            }

            $cdn[] = $tempCdn; // 例如最终组成主线的线路，那么组成备线 1、备线 2、海外的线路的方法和这里相同
        }

        echo sprintf('[%d, %d, %d, %d, %d, %d]', $cdn[0], $cdn[1], $cdn[2], $cdn[3], $cdn[4], $cdn[5]);
        // var_dump($cdn);

        die;

        var_dump(strtoupper(substr(md5('TOKEN_USERNAME'), 8, 16)), strtoupper(substr(md5('TOKEN_PASSWORD'), 8, 16)));
        die;

        $key = 'migu@zq!@#';
        $ymd = date('Ymd');
        $param1 = md5($key . 'param1' . $ymd);
        $param2 = md5($key . 'param2' . $ymd);

        var_dump(substr($param1, 8, 16), substr($param2, 8, 16));

        var_dump($param1, $param2);

        die;
        set_time_limit(0);

        $result = $this->db->fetchOne('SELECT id FROM hash ORDER BY id DESC', Phalcon\Db::FETCH_ASSOC);

        $lastId = 0;
        if ($result) {
            $lastId = $result['id'];
        }

        $hashids = new \Hashids\Hashids('', 6);

        for ($i = $lastId + 1; $i <= $lastId + 50000 + 1; $i++) {
            $code = $hashids->encode($i);
            $length = strlen($code);

            $data = [
                'id' => null,
                'number' => $i,
                'code' => $code,
                'length' => $length,
            ];
            $this->db->insert('hash', array_values($data), array_keys($data));
            // echo $i, ' - ', $hashids -> encode($i), '<br>';
        }

        // var_dump($this->khash(1), $this->khash(2), $this -> khash(null));
        exit;
    }

    public function aaa()
    {
        $arr = [
            '778206',
            '938463',
            '197330',
            '774652',
            '112599',
            '676912',
            '722434',
            '637984',
            '102403',
            '418469',
            '723860',
        ];

        $a = [];
        $b = [];
        foreach ($arr as $k => $v) {
            if ($k % 2 == 0) {
                $a[] = $v;
            } else {
                $b[] = $v;
            }
        }

        $aa = [];
        $bb = [];

        $b = array_reverse($b);

        foreach ($a as $k => $v) {
            if ($k % 2 == 0) {
                $aa[] = $a[$k];

                if (empty($b[$k])) {
                    continue;
                }

                if (empty($b[$k + 1]) && empty($a[$k + 1])) {
                    $bb[] = $b[$k];
                } else {
                    $aa[] = $b[$k];
                }
            } else {
                $bb[] = $a[$k];

                if (empty($b[$k])) {
                    continue;
                }
                $bb[] = $b[$k];
            }
        }

        return [
            'a' => $aa,
            'b' => $bb,
        ];
    }

    public function khash($data)
    {
        static $map = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
        $str = "";
        do {
            $str = $map[bcmod($hash, 62)] . $str;
            $hash = bcdiv($hash, 62);
        } while ($hash >= 1);
        return $str;
    }

    public function test($root = 'urlset', $node = 'url')
    {
        $rootXml = new SimpleXMLElement(sprintf('<?xml version="1.0" encoding="utf-8"?><%s />', $root));

        foreach ($this->config as $v) {
            $nodeXml = $rootXml->addChild($node);
            $nodeXml = $this->extra($nodeXml, $v);
        }

        // header("Content-type: text/xml");
        $rootXml->asXML('test.xml');
    }

    private $config = [
        [
            'loc' => '0',
            'lastmod' => 'null',
            'changefreq' => '',
            'priority' => '1_4',
            'data' => [
                'id' => 'a',
                'name' => '<![CDATA[天龙八部]]>',
            ],
        ],
        [
            'loc' => null,
            'lastmod' => 0,
            'changefreq' => '2_3',
            'priority' => '2_4',
            'data#1' => 1,
            'data#3' => 'a',
            'data#2@777' => [
                'id' => null,
                'name' => 0,
            ],
        ],
        [
            'loc' => '3_1',
            'lastmod' => '3_2',
            'changefreq' => '3_3',
            'priority' => '3_4',
            'data' => [
                'subData@domain' => [
                    'id' => 'b',
                    'name' => [
                        'nickname' => '0',
                        'pic' => 'null',
                    ],
                ],
                'subData2' => [
                    'id' => 1,
                    'name' => '',
                ],
            ],
        ],
    ];

    public function extra($xml, $data, $multi = '#', $value = '@')
    {
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $preg = array_merge(preg_split("/[{$multi}{$value}]+/", $k), [null, null]);
                $title = $preg[0];
                unset($preg[0]);
                array_multisort($preg, SORT_DESC);
                $content = $preg[0];

                $subNode = $xml->addChild($title, $content);

                $this->extra($subNode, $v, $multi, $value);
            } else {
                $xml->addChild(explode($multi, $k)[0], $v);
            }
        }

        return $xml;
    }

    public function indexAction()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');

        foreach ($this->config as $conf) {
            $url = $xml->addChild('url');

            $url->addChild('loc', $conf['loc']); //视频PC端URL
            $url->addChild('lastmod', $conf['lastmod']); //上传时间
            $url->addChild('changefreq', $conf['changefreq']); //hourly
            $url->addChild('priority', $conf['priority']); //权重，全部1

            $data = $url->addChild('data');
            $display = $data->addChild('display');

            $display->addChild('id', $conf['data']['id']); //视频ID
            $display->addChild('sourceTime', $conf['data']['name']); //创建时间
            $display->addChild('name', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //页面title
            $display->addChild('englishName', sprintf('<![CDATA[%s]]>', '')); //英文名，默认空
            $display->addChild('originalName', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //页面title
            $display->addChild('alias', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //别名，默认页面title
            $display->addChild('keyword', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //关键词，页面keyword
            $display->addChild('tags', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //标签，默认空
            $display->addChild('category', sprintf('<![CDATA[%s]]>', '直播')); //视频分类，默认直播
            $display->addChild('type', sprintf('<![CDATA[%s]]>', '电子竞技')); //视频类型，默认电子竞技
            $display->addChild('image', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频封面图
            $display->addChild('detailUrlForWeb', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频URL
            $display->addChild('detailUrlForH5', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频移动端URL
            $display->addChild('detailUrlForApp', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频移动端URL
            $display->addChild('playUrlForWeb', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频URL
            $display->addChild('playUrlForH5', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频移动端URL
            $display->addChild('playUrlForApp', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频移动端URL
            $display->addChild('support', 'pc$$h5$$app'); //可播放方式
            $display->addChild('videoFormat', $conf['data']['name']); //视频格式
            $display->addChild('videoSize', $conf['data']['id']); //视频大小，单位KB
            $display->addChild('host', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //主播名称
            $display->addChild('guest', sprintf('<![CDATA[%s]]>', '')); //嘉宾，默认空
            $display->addChild('contentLocation', sprintf('<![CDATA[%s]]>', '中国')); //地区，默认中国
            $display->addChild('director', sprintf('<![CDATA[%s]]>', '')); //导演，默认空
            $display->addChild('description', $conf['data']['name']); //简介，默认页面description
            $display->addChild('language', sprintf('<![CDATA[%s]]>', '国语')); //语言，默认国语
            $display->addChild('Number1', $conf['data']['name']); //第几期，非lyingman都空
            $display->addChild('Number2', $conf['data']['name']); //视频发布日期，格式20161115
            $display->addChild('isPurchase', 0); //是否收费：0=免费，1=收费
            $display->addChild('isDelete', 0); //是否删除：0=不删除，1=删除
            $display->addChild('definition', '超清'); //视频清晰度，默认超清
            $display->addChild('isExclusive', 1); //是否独家：0=否，1=是
            $display->addChild('isOriginal', 1); //是否原创：0=否，1=是
            $display->addChild('duration', $conf['data']['name']); //时长，单位秒
            $display->addChild('brief', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //摘要，页面description
            $display->addChild('producer', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频作者（主播名称）
            $display->addChild('uploader', sprintf('<![CDATA[%s]]>', '战旗直播')); //上传用户
            $display->addChild('mainActor', $conf['data']['name']); //主演（主播名称）
            $display->addChild('actor', sprintf('<![CDATA[%s]]>', '')); //演员，默认空
            $display->addChild('bps', $conf['data']['name']); //码率
            $display->addChild('isPGC', $conf['data']['name']); //lyingman和旗咔秀1，其余0
            $display->addChild('score', mt_rand(70, 100) / 10); //评分，7.0-10.0随机
            $display->addChild('playcnt', $conf['data']['name']); //总播放
            $display->addChild('playcntDay', ''); //日播放，默认空
            $display->addChild('comment', ''); //总评论数
            $display->addChild('commentDay', ''); //日评论数
            $display->addChild('collection', ''); //总收藏数
            $display->addChild('collectionDay', ''); //日收藏数
            $display->addChild('voteUp', ''); //总支持数（赞/顶）
            $display->addChild('voteUpDay', ''); //日支持数
            $display->addChild('voteDown', ''); //总反对数
            $display->addChild('voteDownDay', ''); //日反对数
            $display->addChild('quote', ''); //总引用数
            $display->addChild('quoteDay', ''); //日引用数
            $display->addChild('channelId', $conf['data']['name']); //视频所属专辑ID
            $display->addChild('channelName', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //视频所属专辑名
            $display->addChild('channelUrl', sprintf('<![CDATA[%s]]>', $conf['data']['name'])); //专辑URL

        }

        header("Content-type: text/xml");
        // echo $xml->asXml();
        $xml->asXML('test.xml');
    }

    public function demo()
    {
        $list = Role::find();
        if (!$list) {
            return false;
        }

        foreach ($list as &$role) {
            $roleId = $role['id'];
            if ($roleId <= 0) {
                $roleId = 400090;
            }

            $role['pid'] = [];
            $role['pid'][] = $this->getPid($role['bindId']);
            $role['pid'][] = $this->getPid($role['pid'][0]);
            $role['pid'][] = $this->getPid($role['pid'][1]);
        }
        unset($v);
    }

    public function getPid($pid)
    {
        if ($pid <= 0) {
            return 0;
        }

        $members = Members::findFirst('agentId = ' . $pid);
        if (!$members) {
            return $pid;
        }

        if ($members['a'] == $members['b']) {
            $pid = $members['a'];
        }

        return (int) $pid;
    }

    public function abc($arr)
    {
        static $a = [];
        foreach ($arr as $v) {
            if (isset($v['children'])) {
                $this->abc($v['children']);
                continue;
            }
            $a[] = $v['id'];
        }

        return $a;
    }
}
