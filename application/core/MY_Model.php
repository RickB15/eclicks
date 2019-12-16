<?php
/**
* MY_Model
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Eclicks
* @package      CI
* @subpackage   Model
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         -
* @since        File available since Release 0.0.0
* @author       Rick Blanskma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles
 */
class MY_Model extends CI_Model {
	//* GLOBAL variables */
	## database
	protected $db_table = Array();

	private $username;
	
	public function __construct()
	{
		parent::__construct();
		
	}

/*
| -------------------------------------------------------------------
|  Database builder
| -------------------------------------------------------------------
|
|	@navigation
|	select
|	insert
|	update
|	delete
|	delete all
|
*/	
	/**
	 * Handles database selects
	 * @param table {string} contains
	 * @param select {array (only string)} contains out of tablenames
	 * @param limit {array (only int) or int} contains a limit in numbers
	 * @param where {array (only string)} contains out of array(tablename => value, tablename => value) etc.
	 * @param encoded {bool} if the database data is encoded
	 * @return query
	 */
	protected function _db_select(String $table, String $select=NULL, $limit=NULL, Array $where=NULL, array $join=NULL, Bool $encoded=FALSE)
	{
		//TODO use $this->db->trans_start(); $this->db->trans_end(); to handle db errors
		if( $select !== NULL && $encoded === FALSE){
			if( $this->_check_type($select, 'string') === TRUE ){
				$this->db->select($select);
			} else {
				//TODO error handling wrong type select
				echo "wrong type 'select'";
				die();
			}
		} else {
			$this->db->select('*');
		}
		
		$this->db->from($table);

		if( $limit !== NULL ){
			if( $this->_check_type($limit, 'int') === TRUE ){
				$this->db->limit($limit);
			} else {
				//TODO error handling wrong type limit
				echo "wrong type 'limit'";
				die();
			}
		}

		if( $join !== NULL ){
			if( is_array($join[0]) ){
				foreach ($join as $value) {
					$join_table = $value[0];
					$conditions = $value[1];
					$type = '';
					$escape = null;
					if( isset($value[2]) ){
						$type = $value[2];
					}
					if( isset($value[3]) ){
						$escape = $value[2];
					}
					$this->db->join($join_table, $conditions, $type, $escape);
				}
			} else {
				$join_table = $join[0];
				$conditions = $join[1];
				$type = '';
				$escape = null;
				if( isset($join[2]) ){
					$type = $join[2];
				}
				if( isset($join[3]) ){
					$escape = $join[2];
				}
				$this->db->join($join_table, $conditions, $type, $escape);
			}			
		}

		if( $encoded === TRUE ){
			if( $where !== NULL ){
				return $this->_encoded_where($where, $select);
			} else {
				if( $select !== NULL ){
					if( $this->_check_type($select, 'string') === TRUE ){
						$this->db->select($select);
					} else {
						//TODO error handling wrong type select
						echo "wrong type 'select'";
						die();
					}
				}
				$query = $this->db->get();
				$data = Array();
				foreach ($query->result_array() as $key => $row) {
					$data[$key] = $this->_decode($row);
				}
				return $data;
			}
		} else {
			if( $where !== NULL ){
				if( $this->_check_where_type($where) === TRUE ){
					$this->db->where($where);
				} else {
					//TODO error handling wrong type select
					echo "wrong type 'where'";
					die();
				}
			}

			$query = $this->db->get();
			return $query;
		}
		$this->db->flush_cache();
	}
	
	/**
	 * Handles database inserts
	 * @param table {string} contains
	 * @param data {array} contains
	 * @param where {array} contains
	 * @return
	 */
	protected function _db_insert(String $table, Array $data, Array $where=NULL, Bool $encoded=FALSE)
	{
		//TODO use $this->db->trans_start(); $this->db->trans_end(); to handle db errors
		if( $where !== NULL ){
			if( $this->_check_where_type($where) === TRUE ){
				if( $encoded === TRUE ){
					//TODO encoded database hadeling
				}
				else {
					$this->db->where($where);
				}
			} else {
				//TODO error handling wrong type select
				echo "wrong type 'select'";
				die();
			}
		}

		$batch = FALSE;
		foreach ($data as $value) {
			if( is_array($value) ){
				$batch = true;
				break;
			}
		}
		if( $batch === TRUE ){
			foreach ($data as $array) {
				if( $this->_check_data($table, $data) === FALSE ){
					//TODO error handling wrong type
					echo "wrong type in array";
					die();
				}
			}
			if( $this->db->insert_batch($table, $data) ){
				return TRUE;
			} else {
				//TODO error handling no insert
				echo $this->db->set($data)->get_compiled_insert($table);
				die();
			}
		} else {
			if( $this->_check_data($table, $data) === TRUE ){
				if( $this->db->insert($table, $data) ){
					return TRUE;
				} else {
					//TODO error handling no insert
					echo $this->db->set($data)->get_compiled_insert($table);
					die();
				}
			}
		}
		return FALSE;
	}

	/**
	 * Handles database updates
	 */
	protected function _db_update(String $table, Array $data, $where=NULL, Bool $encoded=FALSE)
	{
		//TODO use $this->db->trans_start(); $this->db->trans_end(); to handle db errors
		if( $where !== NULL ){
			if( $this->_check_where_type($where) === TRUE ){
				if( $encoded === TRUE ){
					$decoded_where = Array();
					$query = $this->db->get($table);
					foreach ($query->result() as $result) {
						//if {@where} values are in database row
						if( count(array_intersect_assoc($this->_decode($where), $this->_decode($result))) === count($where) ){
							array_push($decoded_where, array_intersect_key((array) $result, array_flip(array_keys($where))));
						}
					}
					$this->db->where($decoded_where[0]);
					unset($decoded_where);
				}
				else {
					$this->db->where($where);
				}
			} else {
				//TODO error handling wrong type select
				echo "wrong type 'select'";
				die();
			}
		}
		
		$this->db->update($table, $data);
		if( $this->db->affected_rows() > 0 ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Handles database deletes
	 * @param table {array or string} contains
	 * @param where {array (only string)} contains
	 * @return
	 */
	protected function _db_delete(String $table, Array $where=NULL, Bool $encoded=FALSE)
	{
		//TODO use $this->db->trans_start(); $this->db->trans_end(); to handle db errors
		if( $where !== NULL ){
			if( $this->_check_where_type($where) === TRUE ){
				if( $encoded === TRUE ){
					//TODO encoded database handling
				} else {
					$this->db->where($where);
				}
			} else {
				//TODO error handling wrong type select
				echo "wrong type 'select'";
				die();
			}
		}

		$this->db->delete($table);
		if( $this->db->affected_rows() > 0 ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Handles database deletes all
	 */
	protected function _db_delete_table(String $table)
	{
		//TODO use $this->db->trans_start(); $this->db->trans_end(); to handle db errors
		if( $this->db->empty_table($table) ){
			return TRUE;
		}
		return FALSE;
	}

/*
| -------------------------------------------------------------------
|  Builder class specifics
| -------------------------------------------------------------------
*/
	/**
	 * 
	 */
	private function _encoded_where(Array $where, String $select=NULL)
	{
		if( $this->_check_where_type($where) === TRUE ){
			$data = Array();
			$query = $this->db->get();
			foreach ($query->result() as $result) {
				//if {@where} values are in database row
				if( count(array_intersect_assoc($this->_decode($where), $this->_decode($result))) === count($where) ){
					array_push($data, $result);
				}
			}

			if( $select !== NULL ){
				$select_array = explode(', ', $select);
				foreach ($data as $array) {
					foreach ($array as $key => $value) {
						if( !in_array($key, $select_array) ){
							unset($array->$key);
						}
					}
				}
			}
			return $data;
		} else {
			//TODO error handling wrong type select
			echo "wrong type 'select'";
			die();
		}
	}

/*
| -------------------------------------------------------------------
|  Checks //TODO change check types (maybe even remove?)
| -------------------------------------------------------------------
*/	
	/**
	 * Check if database tables correspond with the data keys
	 */
	private function _check_data(String $table, Array $data)
	{
		$count = 0;
		$table_fields = $this->db->field_data($table);
		$field_names = array();
		foreach ($table_fields as $field) {
			array_push($field_names, $field->name);
		}
		
		foreach ($data as $key => $value) {
			if( in_array($key, $field_names) ){
				$count++;
			} else {
				//TODO error handling table name not correct
				echo "{$key} name not correct";
				die();
			}
		}
		
		if( $count === count($data) ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * @param data {all} contains the data that needs to be checked
	 * @param type {string} contains the type that all the data needs to have
	 */
	private function _check_type($data, String $type)
	{
		if( is_array($data) ){
			foreach ($data as $value) {
				if( gettype($value) !== $type ){
					//TODO error handling wrong type
					echo "wrong type ".$value;
					return FALSE;
				}			
			}
		} else {
			if( gettype($data) !== $type ){
				//TODO error handling wrong type
				echo "wrong type ".$data;
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * @param data {all} contains the data that needs to be checked
	 * @param types {array} contains the type that all the data needs to have
	 */
	private function _check_types($data, Array $types)
	{
		$wrong_type = true;
		foreach ($types as $type) {
			if( is_array($data) ){
				foreach ($data as $value) {
					if( gettype($value) === $type ){
						$wrong_type = false;
						break;
					}
				}
			} else {
				if( gettype($data) !== $types ){
					//TODO error handling wrong type
					echo "wrong types ".$data;
					return FALSE;
				}
			}
		}
		if( $wrong_type === TRUE ){
			//TODO error handling wrong type
			echo "wrong types ".$value;
			return FALSE;
		}
		
		return TRUE;
	}

	/**
	 * 
	 */
	private function _check_where_type(Array $where)
	{
		$batch = false;
		$types = array('string', 'int');

		//check if array in array
		foreach ($where as $value) {
			if( is_array($value) ){
				$batch = true;
				break;
			}
		}

		if( $batch === TRUE ){
			foreach ($where as $key => $array) {
				if( $this->_check_types($array[$key], $types) === FALSE ){
					//TODO error handling
					echo "type not correct in 'where' array";
					return FALSE;
				}
			}
		} else {
			if( $this->_check_types($where, $types) === FALSE ){
				//TODO error handling
				echo "type not correct in 'where'";
				return FALSE;
			}
		}
		return TRUE;
	}

/*
| -------------------------------------------------------------------
|  Encryption
| -------------------------------------------------------------------
*/
	/**
     * Encode item
     * @param item {array or string} contains an item/items that needs
     * te be encoded
     * @param exeptions {array or string} contains an item/items that
     * don't needs to be encoded
     * @return hased_item {array or string}
     */
    protected function _encode($item, $exeptions=null)
    {
        //import library
        $this->load->library('encryption');

        if( is_array($item) ){
            //multiple items needs to be encrypt
            foreach ($item as $key => $value) {
                if( empty($value) ){
                    //null to make sure encrypt doesn't encrypt this value
                    $item[$key] = null;
                } else {
                    $skip = FALSE;
                    if( !empty($exeptions) ) {
                        if( is_array($exeptions) ){
                            //multiple exeptions
                            foreach($exeptions as $exeption ){
                                if( $key === $exeption ){
                                    //value is in exeptions
                                    $skip = TRUE;
                                }
                            }
                        } elseif( $key === $exeptions ) {
                            //value is exeption
                            $skip = TRUE;
                        }
                        if( $skip === FALSE ){
                            //value isn't in exeptions and needs to be encrypt
                            $item[$key] = $this->encryption->encrypt((string)$value);
                        }
                    } else {
                        //encrypt all values (no exeptions) using encryption library
                        $item[$key] = $this->encryption->encrypt((string)$value);
                    }
                }
            }
        } else {
            //single item needs to be encrypt
            if( empty($item) ){
                //null to make sure encrypt doesn't encrypt this value
                $item = null;
            } else {
                //encrypt value using encryption library
                $item = $this->encryption->encrypt((string)$item);
            }
        }

        return $item;
    }

	/**
     * Decode item
     * @param item {array or string} contains an item/items that needs
     * te be decoded
     * @return dehased_item {array or string}
     */
    protected function _decode($item)
    {
        //import library
		$this->load->library('encryption');
		
		//object to array convert
		if( is_object($item) === TRUE ){
			$item = (array) $item;
		}

        if( is_array($item) ){
            foreach($item as $key => $value) {
                if( $this->encryption->decrypt($value) !== FALSE ){
					$item[$key] = $this->encryption->decrypt($value);
                }
            }
        } else {
            if( $this->encryption->decrypt($item) !== FALSE ){
                $item = $this->encryption->decrypt($item);
            }
		}

        return $item;        
	}
}