<?php

	/**
	 * Created by PhpStorm.
	 * User: Atomic 1
	 * Date: 06/11/14
	 * Time: 11:09 AM
	 */
	class Hatchbuck
	{

		static private $key = 'dGxKOFNXRjc4UFIxTy13bVQ2WVJnUm9iRHJ0NmQwZWtNU3dVVjJKdXkzODE1';
//		static private $key = 'dWwzdHduM1FQbE85cFc2RnNTeEdzTy1jQjBXMHFuaGtFeHJBbVQ5TklnVTE1';
		static private $host = 'https://api.hatchbuck.com/api/v1/contact/';


		public function __construct()
		{
			// @todo
		}

//		Must provide apporpriate method and body data.
		private static function _doRequest($method, $data, $service = '')
		{
			$url     = self::$host . $service . '?api_key=' . self::$key;
			$context = stream_context_create(array(
				'http' => array(
					'method'  => $method,
					'content' => $data,
					'header'  => "Content-Type: application/json\r\n"
				)
			));
			$result  = file_get_contents($url, FALSE, $context);

			return $result;
//return $data;
//return $url;
		}


		public static function search($email)
		{
			$n = '{"emails": [
            {   "address": "'.$email.'"    }

        ]}';

			return self::_doRequest('POST', $n, 'search');
		}


		public static function addNewUser($data)
		{
			return self::_doRequest('POST', $data);
		}


		public static function addTag($email, $d)
		{
			$u    = $email . '/Tags';
			$data = '[' . $d . ']';

			return self::_doRequest('POST', $data, $u);
		}

		public static function delTag($email, $d)
		{
			$u    = $email . '/Tags';
			$data = '[' . $d . ']';

			return self::_doRequest('DELETE', $data, $u);
		}

		public static function updateUser($data)
		{
			//$service = $email;
			return self::_doRequest('PUT', $data);
		}

		public static function startCampaign($email, $data)
		{
			$service = $email . '/Campaign';

			return self::_doRequest('POST', $data, $service);
		}
	}