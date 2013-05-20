<?php 
//模型数据库配置文件，用于实现不同模型绑定不同的数据库实例
$default_db_config =array(
	'host'=>default_db_host,
	'user'=>default_db_username,
	'pass'=>default_db_password,
	'database'=>default_db_database,
	'charset'=>default_db_charset
);
$module_config = array(
	'fruits'=>array(
		'host'=>'117.79.236.110',
		'user'=>'qiji',
		'pass'=>'7g!@#QAZ^&*',
		'database'=>'produce_7g',
		'charset'=>default_db_charset
	),
	'fruitcategorys'=>array(
		'host'=>'117.79.236.110',
		'user'=>'qiji',
		'pass'=>'7g!@#QAZ^&*',
		'database'=>'produce_7g',
		'charset'=>default_db_charset
	),
);
// MemCache配置,可以配置多组
$mcache_config = array(
	'm1'=>array(
		'host'=>DEFAULT_MEMCACHE_HOST,
		'port'=>DEFAULT_MEMCACHE_PORT,
	),
);
//redis配置
$redis_config = array(
	'm1'=>array(
		'host'=>DEFAULT_REDIS_HOST,
		'port'=>DEFAULT_REDIS_PORT,
	),
);
?>