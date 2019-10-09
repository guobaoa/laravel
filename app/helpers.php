<?php

use App\Models\File;
use App\Models\UserFile;
use App\Services\WebSocketClient;

if (!function_exists('load_routes')) {

	/**
	 * 自动装载路由表
	 */
	function load_routes($dir)
	{
		foreach (glob($dir . '/*') as $filename) {
			if (is_dir($filename)) {
				load_routes($filename);
			} elseif (is_file($filename)) {
				require $filename;
			}
		}
	}
}


if (!function_exists('uniqueid')) {

	/**
	 * 创建一个分布式唯一ID
	 *
	 * @return string
	 */
	function uniqueid()
	{
		$uniqid = uniqid(gethostname(), true);
		$md5 = substr(md5($uniqid), 12, 8); // 8位md5
		$uint = hexdec($md5);
		return sprintf('%s%010u', date('Ymd'), $uint);
	}
}

if (!function_exists('short_uniqueid')) {

	/**
	 * 创建一个分布式唯一短ID
	 *
	 * @return string
	 */
	function short_uniqueid($perfix = '')
	{
		return $perfix . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}

if (!function_exists('hash_id')) {

	/**
	 * 计算一个hashID
	 *
	 * @return string
	 */
	function hash_id()
	{
		$data = func_get_args();
		sort($data, SORT_STRING);
		return md5(join(':', $data));
	}
}


//获取ip
if (!function_exists('getIP')) {

	function getIP()
	{
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		} elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		} elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
//end


if (!function_exists('fetchRealUserFile')) {

	/**
	 *  转移服务器文件
	 * @param $filePath
	 * @param null $user
	 * @return UserFile|bool|null
	 */
	function fetchRealUserFile($filePath, $user = null)
	{
		//把图片临时获取到服务器
		if (!$filePath)
			return false;
		// 取得文件是否已经存在。
		$_hash = md5_file($filePath);
		$storage = File::find($_hash);

		if (is_null($storage)) {
			$pathinfo = pathinfo($filePath);

			$_extension = explode('?', $pathinfo['extension']);

			$extension = $_extension[0];
			$_filename = $_hash . '.' . $extension;
			$_storage = base_path('storage/files') . DIRECTORY_SEPARATOR . $_filename;
			// 存图片。
			if (!file_exists($_storage)) {
				copy($filePath, $_storage);
			}
			// 取得图片尺寸。
			$size = getimagesize($_storage);
			$width = $size[0];
			$height = $size[1];
			$mime = mime_content_type($_storage);
			// 存库
			$storage = new File();
			$storage->hash = $_hash;
			$storage->format = $extension ?: '';
			$storage->size = filesize($_storage) ?: 0;
			$storage->width = $width ?: 0;
			$storage->height = $height ?: 0;
			$storage->mime = $mime ?: '';
			$storage->path = $_filename;
			$storage->save();
		}

		$userfile = null;

		if (!is_null($storage)) {
			// 关联用户的文件。

			$userfile = UserFile::where('storage_hash', $_hash)->first();
			if (is_null($userfile)) {
				$userfile = new UserFile();
				if (!is_null($user)) {
					$userfile->user()->associate($user);
				} else {
					$userfile->user_id = 0;
					$userfile->user_type = '';
				}
				$userfile->storage()->associate($storage);
				$userfile->filename = basename($filePath);
				$userfile->path = $storage->path;

				$userfile->save();
			}
		}

		return $userfile;
	}
}

if (!function_exists('fetchUserFile')) {

	function fetchUserFile($path, $user = null)
	{
		if (!$path)
			return null;
		//把图片临时获取到服务器
		$filePath = saveImage($path); //返回文件的绝对地址
		if (!$filePath)
			return null;
		// 取得文件是否已经存在。
		$_hash = md5_file($filePath);
		$storage = File::find($_hash);
		if (is_null($storage)) {
			$pathinfo = pathinfo($filePath);
			$_filename = $_hash . '.' . $pathinfo['extension'];
			$_storage = base_path('storage/files') . DIRECTORY_SEPARATOR . $_filename;
			// 存图片。
			if (!file_exists($_storage)) {
				copy($filePath, $_storage);
			}
			// 取得图片尺寸。
			$size = getimagesize($_storage);
			$width = $size[0];
			$height = $size[1];
			$mime = $size['mime'];
			// 存库
			$storage = new File();
			$storage->hash = $_hash;
			$storage->format = $pathinfo['extension'] ?: '';
			$storage->size = filesize($_storage) ?: 0;
			$storage->width = $width ?: 0;
			$storage->height = $height ?: 0;
			$storage->mime = $mime ?: '';
			$storage->path = $_filename;
			$storage->save();
		}

		$userfile = null;

		if (!is_null($storage)) {
			// 关联用户的文件。
			$userfile = UserFile::where('file_hash', $_hash)->first();
			if (is_null($userfile)) {
				$userfile = new UserFile();
				if (!is_null($user)) {
					$userfile->user()->associate($user);
				} else {
					$userfile->user_id = 0;
					$userfile->user_type = '';
				}
				$userfile->file()->associate($storage);
				$userfile->filename = basename($filePath);
				$userfile->path = $storage->path;
				$userfile->save();
			}
		}

		return $userfile;
	}
}

if (!function_exists('saveImage')) {

	/**
	 * 根据图片的地址保存图片到本地指定的路径
	 * @param type $url 图片的最终地址
	 * @param type $path 文件保存到的路径
	 * @param type $isFollowLocation 图片地址是否302跳转
	 * @return string   返回图片的地址
	 */
	function saveImage($url, $path = '/')
	{
		//定义一个临时文件夹
		$imgTemp = base_path('storage/files') . '/tempImg';
		if (!file_exists($imgTemp)) {
			mkdir($imgTemp, 0755, true);
		}
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //返回文本流
		$imageData = curl_exec($curl);
		curl_close($curl);
		if (preg_match('#404#', $imageData)) {
			return '';
		}

		$files = pathinfo($url);

		if (!array_key_exists("extension", $files)) {
			$filename = sprintf('%s%s.%s', $path, date('Ymd', time()) . md5($files['filename'] . time()), '.jpg');
		} else {
			$_extension = explode('?', $files['extension']);
			$extension = $_extension[0];
			$filename = sprintf('%s%s.%s', $path, date('Ymd', time()) . md5($files['filename'] . time()), $extension);
		}

		if (!file_exists($imgTemp . $filename)) { //创建一个新图片
			$tp = @fopen($imgTemp . $filename, 'a');
			fwrite($tp, $imageData);
			fclose($tp);
			return $imgTemp . $filename;
		} else {
			return $imgTemp . $filename;
		}
	}
}

if (!function_exists('fetchTmpUserFile')) {
	/**
	 *  文件流上传
	 * @param $path
	 * @param null $user
	 * @return UserFile|bool|null
	 */
	function fetchTmpUserFile($tmp, $filename = '', $user = null)
	{
		//把图片临时获取到服务器
		$filePath = saveTmpImage($tmp);//返回文件的绝对地址
		if (!$filePath) return false;
		// 取得文件是否已经存在。
		$_hash = md5_file($filePath);
		$storage = File::find($_hash);
		if (is_null($storage)) {
			$pathinfo = pathinfo($filePath);
			$_filename = $_hash . '.' . $pathinfo['extension'];
			$_storage = base_path('storage/files') . DIRECTORY_SEPARATOR . $_filename;
			// 存图片。
			if (!file_exists($_storage)) {
				copy($filePath, $_storage);
			}
			// 取得图片尺寸。
			$size = getimagesize($_storage);
			$width = $size[0];
			$height = $size[1];
			$mime = $size['mime'];
			// 存库
			$storage = new File();
			$storage->hash = $_hash;
			$storage->format = $pathinfo['extension'] ?: '';
			$storage->size = filesize($_storage) ?: 0;
			$storage->width = $width ?: 0;
			$storage->height = $height ?: 0;
			$storage->mime = $mime ?: '';
			$storage->path = $_filename;
			$storage->save();
		}

		$userfile = null;

		if (!is_null($storage)) {
			// 关联用户的文件。
			$userfile = new UserFile();
			if (!is_null($user)) {
				$userfile->user()->associate($user);
			}
			$userfile->storage()->associate($storage);
			$userfile->filename = $filename;
			$userfile->save();
		}


		return $userfile;
	}
}


if (!function_exists('saveTmpImage')) {
	/**
	 *  t图片流临时文件
	 * @param type $url 图片的最终地址
	 * @param type $path 文件保存到的路径
	 * @param type $isFollowLocation 图片地址是否302跳转
	 * @return string   返回图片的地址
	 */
	function saveTmpImage($tmp, $path = '/')
	{
		//定义一个临时文件夹
		$imgTemp = base_path('storage/files') . '/tempImg';
		if (!file_exists($imgTemp)) {
			mkdir($imgTemp, 0755, true);
		}

		$filename = sprintf('%s%s.%s', $path, date('Ymd', time()) . md5(rand(0, time())), 'jpg');

		@move_uploaded_file($tmp, base_path('storage/files') . '/tempImg' . $filename);
		return $imgTemp . $filename;

	}
}


/**
 *  对象下拉框
 */
if (!function_exists('form_select')) {

	function form_select($name, $list, $selected = null, $options = array(), $default = '', $sid = 'id', $sname = 'name')
	{
		$data = [];
		if ($default) {
			$data[''] = $default;
		}
		if ($list) {
			foreach ($list as $item) {
				$data[$item[$sid]] = $item[$sname];
			}
		}

		return Form::select($name, $data, $selected, $options);
	}
}


if (!function_exists('percent_round')) {

	/**
	 * 百分比显示
	 *
	 * @param $numerator 分子
	 * @param $denominator 分母
	 * @param int $prec 保留几位小数
	 * @return float|string
	 */
	function percent_round($numerator = 0, $denominator = 0, $prec = 2)
	{
		if ($denominator == 0) {
			return "0%";
		}
		return round($numerator / $denominator * 100, $prec) . "%";
	}
}

if (!function_exists('get_real_ip')) {

	/**
	 * 获取真实ip
	 */
	function get_real_ip()
	{
		if (isset($_SERVER)) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else
				if (isset($_SERVER["HTTP_CLIENT_IP"])) {
					$realip = $_SERVER["HTTP_CLIENT_IP"];
				} else {
					$realip = $_SERVER["REMOTE_ADDR"];
				}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$realip = getenv("HTTP_X_FORWARDED_FOR");
			} else
				if (getenv("HTTP_CLIENT_IP")) {
					$realip = getenv("HTTP_CLIENT_IP");
				} else {
					$realip = getenv("REMOTE_ADDR");
				}
		}
		return $realip;
	}
}


if (!function_exists('birthday')) {

	/**
	 *  获取出生日期
	 * @param string $pid
	 * @return mixed
	 */
	function birthday($birthday)
	{
		$age = strtotime($birthday);
		if ($age === false) {
			return false;
		}
		list($y1, $m1, $d1) = explode("-", date("Y-m-d", $age));
		$now = strtotime("now");
		list($y2, $m2, $d2) = explode("-", date("Y-m-d", $now));
		$age = $y2 - $y1;
		if ((int)($m2 . $d2) < (int)($m1 . $d1))
			$age -= 1;
		return $age;
	}

}

if (!function_exists('is_card_no')) {
	/***
	 *  是不是身份证号码
	 * @param $id_card
	 * @return bool
	 */
	function is_card_no($id_card)
	{
		if (strlen($id_card) == 18) {
			return idcard_checksum18($id_card);
		} elseif ((strlen($id_card) == 15)) {
			$id_card = idcard_15to18($id_card);
			return idcard_checksum18($id_card);
		} else {
			return false;
		}
	}

// 计算身份证校验码，根据国家标准GB 11643-1999
	function idcard_verify_number($idcard_base)
	{
		if (strlen($idcard_base) != 17) {
			return false;
		}
		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++) {
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}

// 将15位身份证升级到18位
	function idcard_15to18($idcard)
	{
		if (strlen($idcard) != 15) {
			return false;
		} else {
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false) {
				$idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
			} else {
				$idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
			}
		}
		$idcard = $idcard . idcard_verify_number($idcard);
		return $idcard;
	}

// 18位身份证校验码有效性检查
	function idcard_checksum18($idcard)
	{
		if (strlen($idcard) != 18) {
			return false;
		}
		$idcard_base = substr($idcard, 0, 17);
		if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
			return false;
		} else {
			return true;
		}
	}
}

function getNextArray(&$array, $curr_key)
{
	$next = 0;
	reset($array);

	do {
		$tmp_key = key($array);
		$res = next($array);
	} while (($tmp_key != $curr_key) && $res);


	if ($res) {
		$next = key($array);
	}

	return $next;
}


if (!function_exists('cutstr')) {
	function cutstr($string, $length)
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $info);
		$wordscut = '';
		$j = 0;
		for ($i = 0; $i < count($info[0]); $i++) {
			$wordscut .= $info[0][$i];
			$j = ord($info[0][$i]) > 127 ? $j + 2 : $j + 1;
			if ($j > $length - 3) {
				return $wordscut . " ...";
			}
		}
		return join('', $info[0]);
	}
}


if (!function_exists('getRemindTime')) {
	/**
	 *  计算提醒时间
	 * @param string $time 初始时间  时间戳
	 * @param string $node_time 多少小时后提醒
	 * @param string $node_surplus 剩余多少分钟后提醒
	 * @return string
	 */
	function getRemindTime($time = '', $node_time = '', $node_surplus = '')
	{
		return 0;
		$t = $node_time * 60 * 60 - $node_surplus * 60;
		$time = $time + $t % 3600;
		$l = floor($t / 3600);
		$date = date('Y-m-d H:i:s', $time);
		$more_day = floor($l / 7);
		$mod_day = $l % 7;
		$h = date('H', $time);
		$arr = [
			'09' => '09',
			'10' => '10',
			'11' => '11',
			'14' => '14',
			'15' => '15',
			'16' => '16',
			'17' => '17',
		];

		$y = 0;
		$this_h = '';
		foreach ($arr as $item) {
			if ($y) {
				$mod_day--;
			}
			if ($arr[$h] == $item) {
				$y = 1;
			}
			if (!$mod_day) {
				$this_h = $item;
				break;
			}
		}

		if ($mod_day) {
			$more_day++;
			foreach ($arr as $item) {
				$mod_day--;
				if (!$mod_day) {
					$this_h = $item;
					break;
				}
			}
		}
		return strtotime(date('Y-m-d ' . $this_h . ':i:s', strtotime($date . " +" . $more_day . " day")));
	}
}

if (!function_exists('getPictureUrl')) {
	/**
	 *  获取 图片地址
	 * @param string $picture_id
	 * @return string
	 */
	function getPictureUrl($picture_id = '', $width = 0, $height = 0, $type = 0)
	{
		if ($picture_id) {
			if (env('OSS')) {
				$user_file = UserFile::find($picture_id);
				if (!is_null($user_file) && $user_file->path) {
					$image_view = $width || $height ? '?imageView2/' . $type . '/w/' . $width . '/h/' . $height : '';
					//七牛上传
//					$disk = OSS::disk('oss');
//					return OSS::url($user_file->path).$image_view;
					return "http://" . env('OSS_ENDPOINT') . "/" . $user_file->path;
					//七牛上传 end
				}
			}

			$arr['id'] = $picture_id;
			$width && $arr['width'] = $width;
			$height && $arr['height'] = $height;
			return route('FilePull', $arr);
		}
	}
}


if (!function_exists('getcode')) {
	/**
	 *  获取 获取八位邀请码
	 * @param string $picture_id
	 * @return string
	 */
	function getcode()
	{
		return strtoupper(sprintf('%x', crc32(microtime())));
	}
}

if (!function_exists('getTree')) {
	/***
	 *   树状
	 */
	function getTree($items)
	{
		$tree = array();
		foreach ($items as $item) {
			if (isset($items[$item['pid']])) {
				$items[$item['pid']]['children'][] = &$items[$item['id']];
			} else {
				$tree[] = &$items[$item['id']];
			}
		}

		return $tree;
	}
}

if (!function_exists('getGroupId')) {
	/***
	 *  获取群号
	 */
	function getGroupId($array = [], $prefix = '')
	{
		if ($array) {
			sort($array);

			return ($prefix ? $prefix . "_" : '') . implode('_', $array);
		}
	}
}
if (!function_exists('convertUrlQuery')) {
	function convertUrlQuery($query)
	{
		$queryParts = explode('&', $query);
		$params = array();
		foreach ($queryParts as $param) {
			$item = explode('=', $param);
			$params[$item[0]] = $item[1];
		}
		return $params;
	}
}

if (!function_exists('getUrlQuery')) {
	/**
	 * 将参数变为字符串
	 * @param $array_query
	 * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0&region=0&s=1&page=1' (length=73)
	 */
	function getUrlQuery($array_query)
	{
		$tmp = array();
		foreach ($array_query as $k => $param) {
			$tmp[] = $k . '=' . $param;
		}
		$params = implode('&', $tmp);
		return $params;
	}
}

if (!function_exists('getSignUrl')) {
	/**
	 * 获取签章手机地址
	 */
	function getSignUrl($url, $is_web = '')
	{
		if ($is_web) {
			$new_url = 'https://w.tsign.cn/';
		} else {
			$new_url = 'https://m.tsign.cn/sign';
		}
		$arr = parse_url($url);
//        dump($arr = parse_url($url));
		$arr_query = convertUrlQuery($arr['query']);
//        dump($arr_query);
		if (array_key_exists('flowId', $arr_query) && array_key_exists('accountGid', $arr_query) && array_key_exists('accountOid', $arr_query)) {
			$new_arr = [
				'oid' => $arr_query['accountOid'],
				'gid' => $arr_query['accountGid'],
				'flowId' => $arr_query['flowId'],
			];
			return $new_url . "?" . getUrlQuery($new_arr);
//            dump(getUrlQuery($new_arr));
		}
	}
}


if (!function_exists('getSinaShortUrl')) {
	/**
	 * 调用新浪接口将长链接转为短链接
	 * @param string $source 申请应用的AppKey
	 * @param array|string $url_long 长链接，支持多个转换（需要先执行urlencode)
	 * @return array
	 */
	function getSinaShortUrl($url_long)
	{

		$source = '2929788088';
		// 参数检查
		if (empty($source) || !$url_long) {
			return false;
		}

		// 参数处理，字符串转为数组
		if (!is_array($url_long)) {
			$url_long = array($url_long);
		}

		// 拼接url_long参数请求格式
		$url_param = array_map(function ($value) {
			return '&url_long=' . urlencode($value);
		}, $url_long);

		$url_param = implode('', $url_param);

		// 新浪生成短链接接口
		$api = 'http://api.t.sina.com.cn/short_url/shorten.json';

		// 请求url
		$request_url = sprintf($api . '?source=%s%s', $source, $url_param);

		$result = array();

		// 执行请求
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $request_url);
		$data = curl_exec($ch);
		if ($error = curl_errno($ch)) {
			return false;
		}
		curl_close($ch);

		$result = json_decode($data, true);

		return $result[0]['url_short'];

	}
}


if (!function_exists('object_to_array')) {
	/**
	 *
	 *  对象转数组
	 * @param $obj
	 * @return bool
	 */
	function object_to_array($obj)
	{
		$obj = (array)$obj;
		foreach ($obj as $k => $v) {
			if (gettype($v) == 'resource') {
				return;
			}
			if (gettype($v) == 'object' || gettype($v) == 'array') {
				$obj[$k] = (array)object_to_array($v);
			}
		}

		return $obj;
	}
}
if (!function_exists('request_get')) {
	/**
	 * @param $url
	 * @return bool
	 */
	function request_get($url)
	{
//        dump($url);
		//初始化
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		// 执行后不直接打印出来
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		// 跳过证书检查
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// 不从证书中检查SSL加密算法是否存在
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		// 请求头，可以传数组
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
		]);

		//执行并获取HTML文档内容
		$output = curl_exec($ch);
//        dump($output);

		//释放curl句柄
		curl_close($ch);

		return object_to_array(json_decode($output));
	}
}
if (!function_exists('request_post')) {
	function request_post($url, $data = NULL)
	{
//        dump($url);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		if (!empty($data)) {
			if (is_array($data)) {
				$data = json_encode($data);
			}
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
			]);
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$res = curl_exec($curl);
		return object_to_array(json_decode($res));
	}
}


if (!function_exists('getWeek')) {
	/**
	 * 星期几
	 */
	function getWeek($str)
	{
		$weekarray = array("日", "一", "二", "三", "四", "五", "六");

		if (strtotime($str) !== false) {
			$str = strtotime($str);
		}
		return "星期" . $weekarray[date("w", $str)];
	}
}


if (!function_exists('validateMobile')) {
	/**
	 * 验证手机
	 */
	function validateMobile($str)
	{
		if (preg_match('/^1[3|4|5|7|8][0-9]{9}$/', $str)) {
			return true;
		}
		return false;
	}
}

if (!function_exists('validateIDCard')) {
	/**
	 * 验证身份证是否有效
	 * @param $IDCard
	 * @return bool
	 */
	function validateIDCard($IDCard)
	{
		if (strlen($IDCard) == 18) {
			return check18IDCard($IDCard);
		} elseif ((strlen($IDCard) == 15)) {
			$IDCard = convertIDCard15to18($IDCard);
			return check18IDCard($IDCard);
		} else {
			return false;
		}
	}

//计算身份证的最后一位验证码,根据国家标准GB 11643-1999
	function calcIDCardCode($IDCardBody)
	{
		if (strlen($IDCardBody) != 17) {
			return false;
		}

		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;

		for ($i = 0; $i < strlen($IDCardBody); $i++) {
			$checksum += substr($IDCardBody, $i, 1) * $factor[$i];
		}

		return $code[$checksum % 11];
	}

// 将15位身份证升级到18位
	function convertIDCard15to18($IDCard)
	{
		if (strlen($IDCard) != 15) {
			return false;
		} else {
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
				$IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
			} else {
				$IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
			}
		}
		$IDCard = $IDCard . calcIDCardCode($IDCard);
		return $IDCard;
	}

// 18位身份证校验码有效性检查
	function check18IDCard($IDCard)
	{
		if (strlen($IDCard) != 18) {
			return false;
		}

		$IDCardBody = substr($IDCard, 0, 17); //身份证主体
		$IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码

		if (calcIDCardCode($IDCardBody) != $IDCardCode) {
			return false;
		} else {
			return true;
		}
	}
}
if (!function_exists('addQuerystring')) {
	/**
	 *  新增查询参数
	 * @param $url
	 * @param array $param
	 * @return string
	 */
	function addQuerystring($url, $param = [])
	{
//		$arr = parse_str($url,$arr);
//		if (array_key_exists('query', $arr)) {
//			parse_str($arr['query'], $parr);
//			$param = array_merge($parr, $param);
//		}
		$u = explode('?', $url);
		return $url . (count($u) > 1 ? '&' : '?') . http_build_query($param);
	}
}

if (!function_exists('getDistance')) {

	function getDistance($lat1, $lng1, $lat2, $lng2, $pre = 2)
	{
		//将角度转为狐度
		$radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
		$radLat2 = deg2rad($lat2);
		$radLng1 = deg2rad($lng1);
		$radLng2 = deg2rad($lng2);
		$a = $radLat1 - $radLat2;
		$b = $radLng1 - $radLng2;
		$s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
		return round($s, $pre);
	}
}

if (!function_exists('sendSocketMsg')) {

	function sendSocketMsg($messages)
	{
		/*

		$client = new WebSocketClient('127.0.0.1', 5200);
		if (!$client->connect())
		{
			echo "connect failed \n";
			return false;
		}

		$messages = '{"cmd":"sendMsg", "TocompanyId":"8","message":"1"}';
		if (!$client->send($messages))
		{
			echo $messages. " send failed \n";
			return false;
		}*/
		$client = new \swoole_client(SWOOLE_SOCK_TCP); //同步阻塞
		$ret = $client->connect('127.0.0.1', 5200);
		if (!$ret) {
			echo "Over flow. errno=" . $client->errCode;
			die("\n");
		}
		$messages = '{"cmd":"sendMsg", "TocompanyId":"8","message":"1"}';
		$client->send($messages);
		$data = $client->recv();
		var_dump($data);
		return true;
	}
}

if (!function_exists('getUuid')) {

	function getUuid()
	{
		$str = md5(uniqid(mt_rand(), true));
		$uuid = substr($str, 0, 8) . '-';
		$uuid .= substr($str, 8, 4) . '-';
		$uuid .= substr($str, 12, 4) . '-';
		$uuid .= substr($str, 16, 4) . '-';
		$uuid .= substr($str, 20, 12);
		return $uuid;
	}
}












