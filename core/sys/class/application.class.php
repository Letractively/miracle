<?php 
/**
 * 说明：控制器实现数据库分布及数据库关系映射
 * 作者：RobertZeng <zeng444@163.com>
 * 日期：2011-10-12
 */
class Application{

	
	private $driver;
	
	protected  $_tablename;

	
	//memche开关
	protected $_mcache_on = false;

		//memche配置
	protected $_mcache_config = array();

	//memche实例
	protected $_mcache ; //memche对象
	
	public function __construct(){
		if(!$this->_tablename)  {
			throw_exception('没有绑定的数据表');
		}
		if(defined('MEM_CACHE') && MEM_CACHE ){
			$this->_mcache_on = true;
		}
		
		$this->driver = new mysqlrelation(array(
			'_model_name'=>strtolower(get_class($this)),
			'_validate'=>$this->_validate,
			'_tablename'=>$this->_tablename
		),$this);
		
		
	}

	public function  _last_sql(){
		echo  $this->driver->_last_sql();
	}

	public function _last_insert_id(){
		echo $this->driver->_last_insert_id();
	}
	/**
	 * 建立一个memcaced连接
	 *
	 */
	public function getMemCache(){
		if(is_null($this->_mcache) && $this->_mcache_on){
			global $mcache_config; $this->_mcache_config = $mcache_config;
			$this->_mcache = Mcache::getInstance($this->_mcache_config);
		}
		return $this->_mcache;
	}

	/**
	 * 按条件删除一条表记录
	 *
	 * @param array $condition 数据筛选条件
	 * @return bool 返回布尔值
	 */
	public function delete($condition){
		return $this->driver->delete($condition);
	}

	/**
	 * 对字段自增
	 *
	 * @param mixed $condition 自增的条件
	 * @param array $data 需要自增的数据
	 * @param bool $is_validate 是否验证数据
	 * @return bool
	 */
	public function increase($condition,$data, $is_auto_update_time=true,$is_validate=true){
		return $this->driver->increase($condition);
	}

	/**
	 * 通用表更新数据
	 *
	 * @param mixed string/array $condition 数据筛选条件
	 * @param array $data 更新数据
	 * @param bool $is_auto_update_time 是否自动更新日期更新
	 * @param bool $is_validate 是否对模型进行验证
	 * @return bool
	 */
	public function update($condition ,$data=array() , $is_auto_update_time=true, $is_validate=true){
		return $this->driver->increase($condition ,$data, $is_auto_update_time, $is_validate);
	}
	/**
	 * 通用表更新一条数据(忽略验证)
	 *
	 * @param array $data 插入数据
	 * @return booleen
	 */
	public function save($condition ,$data , $is_auto_update_time=true, $is_validate=false){
		return $this->driver->save($condition ,$data , $is_auto_update_time, $is_validate);
	}

	/**
	 * 通用表插入一条数据
	 *
	 * @param array $data 插入的关联数组
	 * @param bool $is_auto_update_time 是否自动维护更新字段
	 * @param bool $is_auto_insert_time 是否自动维护插入字段
	 * @param bool $is_validate 是否模型层验证
	 * @return bool 
	 */
	public function add($data, $is_auto_update_time=true, $is_auto_insert_time=true,$is_validate=true){
		
		return $this->driver->add($data ,$is_auto_update_time, $is_auto_insert_time,$is_validate);
	}
	
	/**
	 * 通用表替换或插入一条数据
	 *
	 * @param array $data 插入的关联数组
	 * @param bool $is_auto_update_time 是否自动维护更新字段
	 * @param bool $is_auto_insert_time 是否自动维护插入字段
	 * @param bool $is_validate 是否模型层验证
	 * @return bool 
	 */
	public function replace($data, $is_auto_update_time=false, $is_auto_insert_time=false,$is_validate=true){
		
		return $this->driver->replace($data, $is_auto_update_time, $is_auto_insert_time,$is_validate);

	}
	
	/**
	 * 通用表插入一条数据(忽略验证)
	 *
	 * @param array $data 插入数据
	 * @return booleen
	 */
	public function create($data, $is_auto_update_time=true, $is_auto_insert_time=true,$is_validate=false){
		return $this->add($data,$is_auto_update_time,$is_auto_insert_time,$is_validate);
	}
	
	/**
	 * 通用统计表记录数
	 *
	 * @param unknown_type $condition
	 * @param unknown_type $primary_key
	 * @param unknown_type $is_like
	 * @param unknown_type $list
	 * @return unknown
	 */
	public function count($condition=array(),$primary_key='id'){
   		return $this->driver->count($condition,$primary_key);
	}

	/**
	  * 通用表读取一条记录
	  *
	  * @param array/string $condition 筛选条件
	  * @return array
	  */
	public function  view($condition,$order='',$is_validate=true){
		 return $this->driver->view($condition,$order,$is_validate);
	}

	/**
	 * 通用表读取信息
	 *
	 * @param array/string $condition 筛选表条件
	 * @param array/string $order 排序条件
	 * @param int $start 数据起点
	 * @param int $limit 读取条数
	 * @param booleen $is_like 是否LIKE查询
	 * @return array 数组数据
	 */
	public  function lists($condition=array(),$order='',$start=0,$limit=100){
	
		return  $this->driver->lists($condition,$order,$start,$limit);
	}

	/**
	 * 设定查询字段
	 *
	 * @param array/string $array 字段表
	 * @return array 返回对象
	 */
	public  function items($array){
		return  $this->driver->items($array);

	}

	/**
	 * items的别名函数
	 *
	 * @param array/string $array 字段表
	 * @return object 返回对象
	 */
	public  function item($array){
		return $this->items($array);
	}

	/**
	 * 为datagrid构建字段参数
	 *
	 * @param array $array 字段关联数组 array('id'=>'主键'')
	 * @return object 返回对象
	 */
	public function field($array){
	 return $driver->items($array);
	}

	/**
	 * field的别名函数
	 *
	 * @param array/string $array 字段表
	 * @return object 返回对象
	 */
	public function fields($array){
		return $this->field($array);
	}

	/**
	 * 构建gridview,将条件数据构建成table并在table中绑定增删改查
	 *
	 * @param array $condition 查询条件 array('id'=>2)
	 * @param array $desc 排序条件 array('id'=>'desc')
	 * @param array $functions 功能绑定 默认 array('add'=>true,'delete'=>true,'modify'=>true,'limit'=>20,'pagequery'=>'page') 
	 * @return string 返回html
	 */
	public function gridview($condition=array(),$desc=array(),$functions=array()){
		return $this->driver->gridview($condition,$desc,$functions);
	}
	
	public function getname($id,$name='name'){
		return $this->driver->getname($id,$name);
	}
}

?>