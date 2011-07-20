<?php

App::import('Behavior', 'Tree');

class InfiniTreeBehavior extends TreeBehavior {
	public $name = 'ScopedTree';
	
	public $scopeField = null;
	
	public function setup($Model, $config = array()) {
		$return = parent::setup($Model, $config);
		
		return $return;
	}

	public function afterSave($Model, $created) {
		$this->__setScope($Model, $Model->data[$Model->alias]);
		
		return parent::afterSave($Model, $created);
	}
	
	public function beforeDelete($Model) {
		return parent::beforeDelete($Model);
	}
	
	public function beforeSave($Model) {
		$this->__setScope($Model, $Model->data[$Model->alias]);
		
		return parent::beforeSave($Model);
	}

	public function childcount($Model, $id = null, $direct = false) {
		return parent::childCount($Model, $id, $direct);
	}
	
	public function children($Model, $id = null, $direct = false, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null) {
		return parent::children($Model, $id, $direct, $fields, $order, $limit, $page, $recursive);
	}
	
	public function generatetreelist($Model, $conditions = null, $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) {
		$this->__setScope($Model, $conditions);
		return parent::generatetreelist($Model, $conditions, $keyPath, $valuePath, $spacer, $recursive);
	}

	public function getparentnode($Model, $id = null, $fields = null, $recursive = null) {
		return parent::getparentnode($Model, $id, $fields, $recursive);
	}
	
	public function getpath($Model, $id = null, $fields = null, $recursive = null) {
		return parent::getpath($Model, $id, $fields, $recursive);
	}
	
	public function movedown($Model, $id = null, $number = 1) {
		return parent::movedown($Model, $id, $number);
	}
	
	public function moveup($Model, $id = null, $number = 1) {
		return parent::moveup($Model, $id, $number);
	}

	public function recover($Model, $mode = 'parent', $missingParentAction = null) {
		return parent::recover($Model, $mode, $missingParentAction);
	}
	
	public function reorder($Model, $options = array()) {
		return parent::reorder($Model, $options);
	}

	public function removefromtree($Model, $id = null, $delete = false) {
		return parent::removefromtree($Model, $id, $delete);
	}

	public function verify($Model, $scopeValue) {
		$this->__setScope($Model, $scopeValue);
		
		return parent::verify($Model);
	}
	
	public function treeSave($Model, $data, $options = array()) {
		//TODO: Make this semi magic
		$this->__setScope($Model, $options['scope']);
		
		if(!$data){
			return false;
		}

		$Model->transaction();
		
		if($this->__doTreeSave($Model, $data, array('scope' => $options['scope'], 'parent' => null, 'depth' => 0))) {
			$Model->transaction(true);
			return true;
		}
		
		$Model->transaction(false);
		return false;
	}
	
	private function __doTreeSave($Model, $data, $options = array()) {
		$return = false;
		
		//Special case in the first run
		if($options['depth'] > 0) {
			$Model->create();
			if(!$Model->save(array_diff_key(array_merge(array($this->scopeField  => $options['scope'], $this->settings[$Model->alias]['parent'] => $options['parent']), $data), array($Model->alias => $Model->alias)))) {
				return false;
			}
			
			$options['parent'] = $Model->getInsertID();
			
			$return = true;
		}
		
		if(array_key_exists($Model->alias, $data)) {
			$options['depth']++;
			
			foreach($data[$Model->alias] as $childData) {
				if(!$this->__doTreeSave($Model, $childData, $options)) {
					return false;
				};
			}
			
			$return = true;
		}
		
		return $return;
	}
	
	private function __setScope($Model, $data) {
		$scope = null;
		
		//Check if the field is available
		if(!is_array($data)) {
			$scope = $data;
		} elseif(array_key_exists($this->scopeField, $data)) {
			$scope = $data[$this->scopeField];
		} elseif(array_key_exists($Model->alias . '.' . $this->scopeField, $data)) {
			$scope = $data[$Model->alias . '.' . $this->scopeField];
		} else {
			//Try and get the scope field from related data
			debug('not avail');
		}
		
		$this->settings[$Model->alias]['scope'] = $Model->alias . '.' . $this->scopeField . " = '" . $scope . "'";
	}
}
