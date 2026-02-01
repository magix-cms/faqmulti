<?php
/**
 * Class plugins_faqmulti_db
 */
class plugins_faqmulti_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'faqmultis':
					$query = 'SELECT 
								id_faqmulti,
								title_faqmulti,
								desc_faqmulti
							FROM mc_faqmulti ms
							LEFT JOIN mc_faqmulti_content msc USING(id_faqmulti)
							LEFT JOIN mc_lang ml USING(id_lang)
							WHERE ml.id_lang = :lang
							  AND ms.module_faqmulti = :module
							  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
							ORDER BY ms.order_faqmulti';
					if(empty($params['id_module'])) unset($params['id_module']);
					break;
				case 'activefaqmultis':
					$query = 'SELECT 
								id_faqmulti,
								title_faqmulti,
								desc_faqmulti
							FROM mc_faqmulti ms
							LEFT JOIN mc_faqmulti_content msc USING(id_faqmulti)
							LEFT JOIN mc_lang ml USING(id_lang)
							WHERE iso_lang = :lang
							  AND ms.module_faqmulti = :module_faqmulti
							  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
							  AND published_faqmulti = 1
							ORDER BY order_faqmulti';
					if(empty($params['id_module'])) unset($params['id_module']);
					break;
				case 'faqmultiContent':
					$query = 'SELECT ms.*, msc.*
							FROM mc_faqmulti ms
							JOIN mc_faqmulti_content msc USING(id_faqmulti)
							JOIN mc_lang ml USING(id_lang)
							WHERE ms.id_faqmulti = :id';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'faqmultiContent':
					$query = 'SELECT * FROM mc_faqmulti_content WHERE id_faqmulti = :id AND id_lang = :id_lang';
					break;
				case 'lastfaqmulti':
					$query = 'SELECT * FROM mc_faqmulti ORDER BY id_faqmulti DESC LIMIT 0,1';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool
     */
    public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'faqmulti':
				$query = "INSERT INTO mc_faqmulti(module_faqmulti, id_module, order_faqmulti) 
						SELECT :module, :id_module, COUNT(id_faqmulti) FROM mc_faqmulti WHERE module_faqmulti = '".$params['module']."'";
				break;
			case 'faqmultiContent':
				$query = 'INSERT INTO mc_faqmulti_content(id_faqmulti, id_lang, title_faqmulti, desc_faqmulti, published_faqmulti)
						VALUES (:id_faqmulti, :id_lang, :title_faqmulti, :desc_faqmulti, :published_faqmulti)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'faqmultiContent':
				$query = 'UPDATE mc_faqmulti_content
						SET 
							title_faqmulti = :title_faqmulti,
							desc_faqmulti = :desc_faqmulti,
							published_faqmulti = :published_faqmulti
						WHERE id_faqmulti = :id 
						AND id_lang = :id_lang';
				break;
			case 'order':
				$query = 'UPDATE mc_faqmulti 
						SET order_faqmulti = :order_faqmulti
						WHERE id_faqmulti = :id_faqmulti';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	protected function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'faqmulti':
				$query = 'DELETE FROM mc_faqmulti WHERE id_faqmulti IN('.$params['id'].')';
				$params = [];
				break;
			default:
				return false;
		}
		
		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
	}
}