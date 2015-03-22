<?php

class Application_Model_Users extends Zend_Db_Table_Abstract
{
//    protected $_name = "users";
//    
//    function addUser($data){
//        $row = $this->createRow();
//        $row->name = $data['name'];
//        $row->password = md5($data['password']);
//        $row->email = $data['email'];
//        $row->country = $data['country'];
//        $row->signature = $data['signature'];
//        $row->image = $data['image'];
//        $row->gender = $data['gender '];
//        
//        return $row->save();
//    }
//    
//    function listUsers(){
//        
//        return $this->fetchAll()->toArray();
//    }
//    
//    function getUserById($id){
//        return $this->find($id)->toArray();
//    }
//            
//    function editUser($data){
//        $this->update($data, "id=".$data['id']);
//        return $this->fetchAll()->toArray();
//    }
//    
//    function deleteUser($id){
//        return $this->delete("id=$id");
//    }
     protected $_name = "users";
    
    function addUser($data){
        $row = $this->createRow();
        $row->name = $data['name'];
        $row->password = md5($data['password']);
        $row->email = $data['email'];
        $row->country = $data['country'];
        $row->signature = $data['signature'];
        $row->image = $data['image'];
        $row->gender = $data['gender '];
        
        return $row->save();
    }
    
    function listUsers(){
        
        return $this->fetchAll()->toArray();
    }
    
    function getUserById($id){
        return $this->find($id)->toArray();
    }
            
    function editUser($data){
        
        if (empty($data['image'])) {
           unset($data['image']);
        }
        if (empty($data['signature'])) {
           unset($data['signature']);
        }
        
        if (empty($data['password'])) {
           unset($data['password']);
        }         
        $data['password'] = md5($data['password']);         
        $this->update($data, "id=".$data['id']);
        return $this->fetchAll()->toArray();
    }
    
    function deleteUser($id){
        return $this->delete("id=$id");
    }
    
      
    function checkUnique($email)
    {
        $select = $this->_db->select()
                            ->from($this->_name,array('email'))
                            ->where('email=?',$email);
        $result = $this->getAdapter()->fetchOne($select);
        if($result){
            return true;
        }
        return false;

}

    function banuser($id,$ban){
        echo "gfdgd".$ban;
        if($ban==0)
        {
            $banned = array(
           'is_banned'      => '1');  
        }
        
        if($ban==1)
        {
            $banned = array(
           'is_banned'      => '0');  
        }
        return $this->update($banned, "id=".$id);
      
    }


}

