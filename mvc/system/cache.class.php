<?php defined('APP') or die;
class cache {
	protected  $cache = NULL;
	
    public function __construct( $config = array(), $type = 'memcache' ) {
		$class = $type.'Cache';
		//$this->cache = new $class( $config );
    }

	//读取缓存

    public function get($key) {
		return $this->cache->get($key);   
    }
	
	//设置缓存

    public function set($key, $value, $expire = 1800) {
		return $this->cache->set($key, $value, $expire);
    }
	
	//自增1

	public function inc($key, $value = 1) {
		return $this->cache->inc($key, $value);    
	}
	
	//自减1

	public function des($key, $value = 1) {
		return $this->cache->des($key, $value);    
	}
	
	//删除

	public function del($key) {
		return $this->cache->del($key);
	}
	
	//清空缓存

    public function clear() {
		return $this->cache->clear();    
	}
}

// file
class fileCache extends Cache{
	public function __construct( $config = array()) {
		
    }
	
	public function set($key, $value, $expire = 1800) {	
		$strlen = file_put_contents($file, $content);
		@chmod($file, 0777);
		return $strlen;
    }
	
	
}

// memcache
class memcacheCache extends cache{
	private $mmc = NULL;
    private $group = ''; 
    private $ver = 0;
    public function __construct( $memConfig = array() ) {
		$this->mmc = new Memcache;
		if( empty($memConfig) ) {
			$memConfig['MEM_SERVER'] = array(array('127.0.0.1', 11211));
			$memConfig['MEM_GROUP'] = '';
		}
		foreach($memConfig['MEM_SERVER'] as $config) {
			call_user_func_array(array($this->mmc, 'addServer'), $config);
		}
		$this->group = $memConfig['MEM_GROUP'];
		$this->ver = intval( $this->mmc->get($this->group.'_ver') );
    }

	//读取缓存

    public function get($key) {
		return $this->mmc->get($this->group.'_'.$this->ver.'_'.$key);
    }
	
	//设置缓存

    public function set($key, $value, $expire = 1800) {
		return $this->mmc->set($this->group.'_'.$this->ver.'_'.$key, $value, 0, $expire);
    }
	
	//自增1

	public function inc($key, $value = 1) {
		 return $this->mmc->increment($this->group.'_'.$this->ver.'_'.$key, $value);
    }
	
	//自减1

	public function des($key, $value = 1) {
		 return $this->mmc->decrement($this->group.'_'.$this->ver.'_'.$key, $value);
    }
	
	//删除

	public function del($key) {
		return $this->mmc->delete($this->group.'_'.$this->ver.'_'.$key);
	}
	
	//全部清空
    public function clear() {
        return  $this->mmc->set($this->group.'_ver', $this->ver+1); 
    }	
}







