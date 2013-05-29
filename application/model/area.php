<?php
/*
 * 功能：地区服务层
 * author:warpath
 * date2013年 05月 19日 星期日 18:25:22 PDT:
 * 版权：Copyright@2008-2015 quwancn Inc All Rights Reserved
 */
class Area extends Model
{
	
	/**
	 * 返回省市区
	 * @return array
	 */
	public function getRegionalAll()
	{
		$result = array();
		$dao = self::getDao('area');
		$province = $dao->getAll('AreaID as id,AreaName as name', 'RootID = 0');
		$data = array();

		foreach($province as $key => $value) {
			$data[$value['id']] = $value;
			$city = $dao->getAll('AreaID as id,AreaName as name', 'RootID = ' . $value['id']);
			if (!empty($city)) {
				foreach($city as $k => $v) {
					$data[$value['id']]['city'][$v['id']] = $v;
					$region = $dao->getAll('AreaID as id,AreaName as name', 'RootID = ' . $v['id']);
					if (!empty($region)) {
						foreach($region as $i => $j) {
							$data[$value['id']]['city'][$v['id']]['area'][$j['id']] = $j;
						}
					}
				}
			}
		}
		$result['item'] = $data;
		return $result;
	}
}
?>
