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
			array_ex( $argall[0], $argall[$i] );
		}
	}
	return $argall[0];
}