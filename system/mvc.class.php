<?php defined('APP') or die;

// 模型
class Model 
{
    public  $db  = NULL; // 当前数据库操作对象
	public  $sql = '';	//sql语句，主要用于输出构造成的sql语句
    protected $options = array(); // 查询表达式参数	
	protected $config = array(); // 查询表达式参数	
	
    public function __construct() 
	{
		$config = config::get();
		$this->config = array(
			'DB_HOST'    => $config['DB_HOST'],
			'DB_USER'    => $config['DB_USER'],
			'DB_PWD'     => $config['DB_PWD'],
			'DB_PORT'    => $config['DB_PORT'],
			'DB_NAME'    => $config['DB_NAME'],
			'DB_PREFIX'  => $config['DB_PREFIX'],
			'DB_CHARSET' => $config['DB_CHARSET']
		);
		$this->options['field'] = '*';	//默认查询字段
		$this->connect();
    }
	
	//连接数据库
	public function connect() 
	{
		require_once( dirname(__FILE__) . '/mysql.class.php' );
		$this->db = new mysql( $this->config );	//实例化数据库驱动类	
	}
	
	//设置表，$$ignore_prefix为true的时候，不加上默认的表前缀
	public function table($table,$simple=null, $ignorePre = false) 
	{
		if ( $ignorePre ) {
			$this->options['table'] = $table.' '.$simple;
		} else {
			$this->options['table'] = $this->config['DB_PREFIX'] . $table.' '.$simple;;
		}
		return $this;
	}
	
	
	//回调方法，连贯操作的实现
    public function __call($method, $args) {
		$method = strtolower($method);
        if ( in_array($method, array('field','data','where','group','having','order','limit')) ) 
		{
            $this->options[$method] = $args[0];	//接收数据
			if( $this->options['field'] =='' ) $this->options['field'] = '*'; 
			return $this;	//返回对象，连贯查询
        } else{
			new Error('方法在类中没有定义') ;
		}
    }
	
	//执行原生sql语句，如果sql是查询语句，返回二维数组
    public function query($sql, $params = array(), $is_query = false) 
	{
        if ( empty($sql) ) return false;
		$this->sql = $sql;
		//判断当前的sql是否是查询语句
		if ( $is_query || stripos(trim($sql), 'select') === 0 ) {
			$query = $this->db->query($this->sql, $params);		
			while($row = $this->db->fetchArray($query)) {
				$data[] = $row;
			}
			return $data;				
		} else {
			return $this->db->execute($this->sql, $params); //不是查询条件，直接执行
		}
    }
	
	//统计行数
	public function count() 
	{
		$table = $this->options['table'];	//当前表
		$field = 'count(*)';//查询的字段
		$where = $this->_parseCondition();	//条件
		$table_array = $this->options['add_table'];//多表
		if(is_array($table_array)){
			foreach ($table_array as $value) {
				$table_add.=$value['table'];
			}
		}
		$this->sql = "SELECT $field FROM $table $table_add $where";	//这不是真正执行的sql，仅作缓存的key使用
		$data = $this->db->count($table,$table_add, $where);
		$this->sql = $this->db->sql; //从驱动层返回真正的sql语句，供调试使用
		return $data;
	}
	
	//只查询一条信息，返回一维数组	
    public function find() 
	{
		$this->options['limit'] = 1;	//限制只查询一条数据
		$data = $this->select();
		return isset($data[0]) ? $data[0] : false;
     }
	 
	//查询多条信息，返回数组
     public function select() 
	 {
		$table = $this->options['table'];	//当前表
		$field = $this->options['field'];	//查询的字段
		$where = $this->_parseCondition();	//条件
		$table_array = $this->options['add_table'];//多表
		if(is_array($table_array)){
			foreach ($table_array as $value) {
				$table_add.=$value['table'];
			}
		}
		return $this->query("SELECT $field FROM $table $table_add $where", array(), true);
     }
	 
	 //获取一张表的所有字段
	 public function getFields() 
	 {
		$table = $this->options['table'];
		$this->sql = "SHOW FULL FIELDS FROM {$table}"; //这不是真正执行的sql，仅作缓存的key使用
		$data = $this->db->getFields( $table );
		$this->sql = $this->db->sql; //从驱动层返回真正的sql语句，供调试使用
		return $data;
	}
	
	 //插入数据
    public function insert( $replace = false ) 
	{
		$table = $this->options['table'];	//当前表
		$this->format_data_by_fill($this->options['data']); //过滤多余字段
		$data = $this->_parseData('add');	//要插入的数据
		$INSERT = $replace ? 'REPLACE' : 'INSERT';
        $this->sql = "$INSERT INTO $table $data" ;
        $query = $this->db->execute($this->sql);
		if ( $this->db->affectedRows() ) {
			 $id = $this->db->lastId();
			 return empty($id) ? $this->db->affectedRows() : $id;
		}
        return false;
    }
	
	//替换数据
	public function replace() 
	{
		return $this->insert( true );
    }
	
	//修改更新
    public function update() 
	{
		$table = $this->options['table'];	//当前表
		$this->format_data_by_fill($this->options['data']); //过滤多余字段
		$data = $this->_parseData('save');	//要更新的数据
		$where = $this->_parseCondition();	//更新条件
		if ( empty($where) ) return false; //修改条件为空时，则返回false，避免不小心将整个表数据修改了
			
        $this->sql = "UPDATE $table SET $data $where" ;
	    $query = $this->db->execute($this->sql);
		return $this->db->affectedRows();
    }
	
	//删除
    public function delete() 
	{
		$table = $this->options['table'];	//当前表
		$where = $this->_parseCondition();	//条件
		if ( empty($where) ) return false; //删除条件为空时，则返回false，避免数据不小心被全部删除
			
		$this->sql = "DELETE FROM $table $where";
        $query = $this->db->execute($this->sql);
		return $this->db->affectedRows();
    }
	
	//数据过滤
	public function escape($value) 
	{
		return $this->db->escape($value); 
	}
	
	//返回sql语句
    public function getSql() 
	{
        return $this->sql;
    }


	//解析数据  
	private function _parseData($type) 
	{
		$data = $this->db->parseData($this->options, $type);
		$this->options['data'] = '';
		return $data;
	}
	
	//解析条件
	private function _parseCondition() 
	{
		$condition = $this->db->parseCondition($this->options);
		$this->options['where'] = '';
		$this->options['group'] = '';
		$this->options['having'] = '';
		$this->options['order'] = '';
		$this->options['limit'] = '';
		$this->options['field'] = '*';		
		return $condition;		
	}

//==================================添加内容========================================
	//格式化字段
    public function format_data_by_fill(array $data=array())
	{
        $defaultData=$this->fields_default();
        $array=array();
        if(empty($data)) return $array;
        foreach ($defaultData as $key => $value) {
        	if(isset($data[$key])){
        		$array[$key]=$data[$key];
        	}
        }
        $this->options['data']=$this->quote_array($array); 
    }

	//获取一张表的字段名
	public function fields_default() 
	{
		$data=$this->getFields();
		foreach ($data as $field) {
			$fields_default[$field['Field']]=$field['Default'];
		}
		return $fields_default;
	}

	//格式化字段类型
	public function quote_array(&$valueArr)
	{
        return array_map(array(&$this,'quote'), $valueArr);
    }

    public function quote($value) 
	{
        if (is_null($value)) return 'NULL';
        if (is_bool($value)) return $value ? 1 : 0;
        if (is_int($value)) return (int) $value;
        if (is_float($value)) return (float) $value;
        if (@get_magic_quotes_gpc())  $value = @stripslashes($value);
        return $value;
    }

    //添加表
	public function add_table($table,$simple=null,$where=null, $relation='AND' ,$ignorePre = false) 
	{
		if ($ignorePre) {
			 $this->options['add_table'][$simple]['table'] = ','.$table.' '.$simple;
		} else {
			$this->options['add_table'][$simple]['table'] = ','. $this->config['DB_PREFIX'] . $table.' '.$simple;
		}
		$this->options['add_table'][$simple]['where'] =' '.$relation.' '.$where;
		return $this;
	}

	//清除查询条件
    public function clear_where() 
	{
        $this->options['add_table']=null;
    }
}

// 视图
class View
{
	private $config = array();
    private $data   = array();
    private $tplDir = '';
	private $compiledDir = '';
	private $cacheDir = '';
	

    public function __construct()
	{
		$this->cache  = new Cache(); 
		$this->config = config::get();
        $this->tplDir = DIR_THEME . $this->config['THEME'] . DS;
		$this->compiledDir = DIR_COMPILED;
		$this->cacheDir = DIR_CACHE_TPL;
    }
	
    /**
     * 设置模板变量
     */
    public function assign($k, $v) 
	{
        $this->data[$k] = $v;
    }
	
    /**
     * 渲染模板并输出
     */
    public function render($tplName) 
	{
		$tplName = trim($tplName);
		
		// 没有模板名
		if(!$tplName){
			$tplName = $this->config['controller']. '.' . $this->config['action'];
		};
		
		// 模板文件名
		$tplFile  = $this->tplDir . $tplName. '.html';
		
		// 判断模板文件是否存在
		if(!file_exists($tplFile)){
			new Error('Error: 模板 '.$cacheFile.' 不存在！', 500) ;	
		}
		
		// 编译文件名
		$compileFile  = $this->compiledDir . $tplName. '.php';
		
		// 缓存文件名
		$cacheFile  = $this->cacheDir . $tplName. '.html';

		// 读取缓存
		$isCache = $this->config['CACHE_TPL'];
		if($isCache){
			if(file_exists($cacheFile) && file_exists($compileFile)){
                //是否修改过编译文件或者模板文件
                if(filemtime($cacheFile)>=filemtime($compileFile) && filemtime($compileFile)>filemtime($tplFile)){
                    include $cacheFile;
                    return;
                }
            }
		}
		

		// 编译模板
        if(!file_exists($compileFile) || (filemtime($compileFile) < filemtime($tplFile))){
            $this->compile($tplName);
        }
		
		// 导入数据
		extract($this->data);

		
		//生成缓存文件
		if($isCache){
			
			if(!is_dir($this->cacheDir) && !mk_dir($this->cacheDir)){
				new Error('ERROR:"' . $tplName . '"缓存目录创建失败！', 500) ;
			}
			
			ob_start();
			include $compileFile;
			// 获取缓冲区内容
			$content = ob_get_contents();
			
			// 清除缓冲区
			ob_end_clean();

            file_put_contents($cacheFile,$content);
			
			//载入缓存文件
            include $cacheFile;
		}else{
			//载入编译文件
			include $compileFile;
		}
		unset($tplName);
	}
	
	protected function compile($tplName)
	{
		if(!is_dir($this->compiledDir) && !mk_dir($this->compiledDir)){
			new Error('ERROR:"' . $tplName . '"编译目录创建失败！', 500) ;
		}
		$tplFile  = $this->tplDir . $tplName. '.html';
		$compileFile  = $this->compiledDir . $tplName. '.php';
		$content = file_get_contents($tplFile);
		$content = $this->parse($content);
		if(!file_put_contents($compileFile, $content)){
			new Error('ERROR:"' . $tplName . '"编译失败！', 500) ;
		}
	}
	
	/**
     * 解析模板
     */
	protected function parse($tpl)
	{
		$tpl = preg_replace("/([\n\r]+)\t+/s","\\1",$tpl);
		$tpl = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}",$tpl);
		$tpl = preg_replace("/\{url\s+(\S+)\}/","<?php echo getUrl('\\1');?>",$tpl);
		$tpl = preg_replace("/\{include\s+(.+)\}/","<?php \$this->render('\\1'); ?>",$tpl);
		$tpl = preg_replace("/\{php\s+(.+)\}/","\n<?php \\1?>",$tpl);
		$tpl = preg_replace("/\{if\s+(.+?)\}/","<?php if(\\1) { ?>",$tpl);
		$tpl = preg_replace("/\{else\}/","<?php } else { ?>",$tpl);
		$tpl = preg_replace("/\{elseif\s+(.+?)\}/","<?php } elseif (\\1) { ?>",$tpl);
		$tpl = preg_replace("/\{\/if\}/","<?php } ?>",$tpl);
		$tpl = preg_replace("/\{foreach\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2){?>",$tpl);
		$tpl = preg_replace("/\{foreach\s+(\S+)\s+(\S+)\s+(\S+)\}/","<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3){?>",$tpl);
		$tpl = preg_replace("/\{\/foreach\}/","<?php }?>",$tpl);
		$tpl = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\(([^{}]*)\))\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/","<?php echo \\1;?>",$tpl);
		$tpl = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "addquote('<?php echo \\1;?>')",$tpl);
		$tpl = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>",$tpl);
		return $tpl;
	}
}

// 控制器
class Controller 
{
    public function __construct()
	{
		$this->data   = array();
		$this->config = config::get();
		$this->cache  = new Cache();
		$this->Model  = new Model();
        $this->View   = new View();
		if(method_exists($this, '_construct')){
			 $this->_construct();
		}
    }
}
