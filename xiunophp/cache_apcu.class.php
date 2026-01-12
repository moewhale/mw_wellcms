<?php

class cache_apcu {
        
        public $conf = array();
        public $link = NULL;
        public $cachepre = '';
        public $errno = 0;
        public $errstr = '';
        
        public function __construct($conf = array()) {
                // 1. 檢查函數改為 apcu_fetch
                if(!function_exists('apcu_fetch')) {
                        return $this->error(-1, 'APCu 扩展没有加载，请检查您的 PHP 版本');
                }
                $this->conf = $conf;
                $this->cachepre = isset($conf['cachepre']) ? $conf['cachepre'] : 'pre_';
        }

        public function connect() {
        }

        public function set($k, $v, $life) {
                // 2. apc_store -> apcu_store
                return apcu_store($k, $v, $life);
        }

        public function get($k) {
                // 3. apc_fetch -> apcu_fetch
                $r = apcu_fetch($k);
                if(FALSE === $r) $r = NULL;
                return $r;
        }

        public function delete($k) {
                // 4. apc_delete -> apcu_delete
                return apcu_delete($k);
        }

        public function truncate() {
                // 5. apcu_clear_cache 不需要傳入 'user' 參數，因為它只支持用戶緩存
                return apcu_clear_cache();
        }

        public function error($errno = 0, $errstr = '') {
                $this->errno = $errno;
                $this->errstr = $errstr;
                // 確保 DEBUG 常量已定義，否則會報錯
                if (defined('DEBUG') && DEBUG) {
                    trigger_error('Cache Error:'.$this->errstr);
                }
        }

        public function __destruct() {
        }
}

?>
