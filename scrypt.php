<?php

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//
//	Copyright (c) 2023 scripcrypt
//	Released under the MIT license
//	scripcrypt@gmail.com
//
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

//-------------------------------------------------------
//	array mix
//	@param 配列,配列,配列,......
//	@return 配列
//-------------------------------------------------------
function array_mix( $array = [] ) {
	$argall = func_get_args();
	//	foreach( $argall as $aga ) {
	for ( $i = 1, $ci = count( $argall ); $i < $ci; $i++ ) {
		if ( is_array( $argall[$i] ) ) {
			__array_ex( $argall[0], $argall[$i] );
		}
	}
	return $argall[0];
}

	function __array_ex( &$array0, &$array2 ) {
		if ( is_array( $array2 ) ) {
			foreach ( $array2 as $key2 => &$val2 ) {
				if ( isset( $array0[$key2] ) && is_array( $array0[$key2] ) && isset( $val2 ) && is_array( $val2 ) ) {
					array_ex( $array0[$key2], $val2 );
				}
				else {
					if ( isset( $val2 ) && $val2 != '' ) {
						$array0[$key2] = $val2;
					}
				}
			}
		}
	}


//--------------------------------------------------------------
//	object to array
//	@param array/object
//	@return array
//--------------------------------------------------------------
function obj2arr($obj) {
	if ( !is_object($obj) && !is_array($obj) ) { return $obj; }
	elseif ( is_object($obj) ){ $arr = (array)$obj; }
	else{ $arr = $obj; }

	foreach ($arr as &$a) {
		$a = obj2arr($a);
	}
	return $arr;
}


//--------------------------------------------------------------
//	Remove Empty Element from Array
//	@param 配列
//	@return 配列
//--------------------------------------------------------------
function remove_empty_array( &$arr ) {
	if ( is_array( $arr ) ) {
		foreach ( $arr as $i => $v ) {
			if ( is_array( $v ) ) {
				if ( count( $v ) === 0 ) {
					unset( $arr[$i] );
				}
				else {
					remove_empty_array( $arr[$i] );
				}
			}
		}
	}
}


//--------------------------------------------------------------
//	ksort recursive
//		@param array, sort_flag
//			SORT_REGULAR - compare items normally; the details are described in the comparison operators section
//			SORT_NUMERIC - compare items numerically
//			SORT_STRING - compare items as strings
//			SORT_LOCALE_STRING - compare items as strings, based on the current locale. It uses the locale, which can be changed using setlocale()
//			SORT_NATURAL - compare items as strings using "natural ordering" like natsort()
//			SORT_FLAG_CASE - can be combined (bitwise OR) with SORT_STRING or SORT_NATURAL to sort strings case-insensitively
//			...https://www.php.net/manual/en/function.ksort.php
//		@return array
//--------------------------------------------------------------
function ksortRecursive(&$array, $sort_flag = SORT_REGULAR) {
	if (!is_array($array)) return false;
	ksort($array, $sort_flag);
	foreach ($array as &$arr) {
			ksortRecursive($arr, $sort_flag);
	}
	return true;
}


//--------------------------------------------------------------
//	path glue
//		@param array:['path','path/abc','path/ddd/index.html']
//		@return $path='abc/def/ghi'
//--------------------------------------------------------------
function pathglue() {
	if ( func_num_args() == 0 ) {
		return null;
	}
	$args = func_get_args();
	foreach ( $args as $i => $arg ) {
		//	for( $i=0,$ci=func_num_args();$i<$ci;$i++ ) {
		if ( is_array( $arg ) ) {
			array_splice( $args, $i, 1, $arg );
		}
	}
	$path = preg_replace( '#/{2,}#', '/', implode( '/', ( array )$args ) );
	// front slash adjustment
	if ( !preg_match( '#^/#', $args[0] ) ) {
		$path = preg_replace( '#^/#', '', $path );
	}
	// ../ に対応
	//	foreach( range( 0,100 ) as $j ) {
	for ( $j = 0; $j < 100; $j++ ) {
		$path2 = $path;
		$path = preg_replace( '#[^/]+/\.\./#u', '', $path );
		if ( $path2 == $path ) {
			break;
		}
	}
	if ( mb_strlen( $path ) > 1 ) {
		$path = preg_replace( '#/$#u', '', $path );
	}
	return $path;
}


//--------------------------------------------------------------
//	get extention
//	@param string:ファイル名
//	@return 拡張子
//--------------------------------------------------------------
function get_extention( $filename ) {
	if ( trim( $filename ) === '' ) {
		return;
	}
	$arr = explode( '.', $filename );
	$ext = array_pop( $arr );
	return $ext;
}


//--------------------------------------------------------------
//	is_dir as for Multibyte String Function
//	@param directory-path
//	@return boolean
//--------------------------------------------------------------
function mb_is_dir( $ap ) {
	global $ini;
	$ini['encoding']['system'] = $ini['encoding']['system'] ?? 'utf-8';
	$ap = mb_convert_encoding( $ap, $ini['encoding']['system'] );
	return is_dir( $ap ) ? true : false;
}


//--------------------------------------------------------------
//	is_file as for Multibyte String Function
//	@param file-path
//	@return boolean
//--------------------------------------------------------------
function mb_is_file( $ap, $dir = null ) {
	global $ini;
	$ini['encoding']['system'] = isset( $ini['encoding']['system'] ) ? $ini['encoding']['system'] : 'utf-8';
	$ap = mb_convert_encoding( $ap, $ini['encoding']['system'] );
	return !is_null( $dir ) ? ( is_dir( $ap ) ? true : false ) : ( is_file( $ap ) ? true : false );
}


//--------------------------------------------------------------
//	create random string
//		@param len ..> string length
//			"n" => Number
//			"u" => Upper Alphabet
//			"l" => Lower AlPhabet
//			"s" => Symbol
//			default is "nul"
//	@return string
//--------------------------------------------------------------
function create_random_key($len=8,$types="nul") {
	$randomSeeds = [
		"n" => "0123456789",
		"u" => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
		"l" => "abcdefghijklmnopqrstuvwxyz",
		"s" => ",.;:!?#@$%^&'(){}[]_=+/*\\-\"<>`|~"
	];

	$types = strtolower($types);
	$types = $types === "all" ? "nuls" : $types;
	$str="";

	for ( $i=0,$ci=strlen($types); $i<$ci; $i++ ) {
		if ( strpos("nuls",$types[$i]) !== false ) {
			$str .= $randomSeeds[$types[$i]];
		}
	}

	return substr(str_shuffle(str_repeat($str,10)),0,$len);
}