<?php

/**
 *  地理位置搜索
 * 
 *  @param float $value
 */
function returnSquarePoint($lng,$lat,$distance = 2)
{
	$earthRadius = 6371;
	
	$dlng =  2 * asin(sin($distance / (2 * $earthRadius)) / cos(deg2rad($lat)));
	$dlng = rad2deg($dlng);
	
	$dlat = $distance/$earthRadius;
	$dlat = rad2deg($dlat);
	
	return array('left_top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),'right_top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),'left_bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),'right_bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng));
}

/**
 *  根据 region ID 获取完整省市路径
 * 
 *  @param Int $regionId
 */
function getRegionPath($cityId,$countyId = '') 
{
	$regionId = empty($countyId) ? $cityId : $countyId;
	
	$model = new Model_Region();
	$r = $model->find($regionId)->current();
	
	if (empty($r)) 
	{
		return false;
	}
	
	return $r->getPath();
}

?>