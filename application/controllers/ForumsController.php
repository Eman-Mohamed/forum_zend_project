<?php

class ForumsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $authorization =Zend_Auth::getInstance();
        if(!$authorization->hasIdentity()) 
        {           
            $this->redirect("error/error");
        }
    }

    public function indexAction()
    {
        // action body
    }
    
    public function addAction()
    {
        $form  = new Application_Form_Forum();
       
       if($this->_request->isPost()){
           if($form->isValid($this->_request->getParams())){
               $forum_info = $form->getValues();
             
               $name=$forum_info["name"];
               $catgId=$forum_info["cat_id"];
               $forum_model = new Application_Model_Forums();
               $result=$forum_model->checkForums(array($catgId,$name));
               if($result)
               {
                   echo "There is a forum with the same name in this category";
               }
               else
               {
                  
                    $ext = pathinfo($forum_info["image"], PATHINFO_EXTENSION);
                    $upload = new Zend_File_Transfer_Adapter_Http();  
                    $upload->setDestination("/var/www/html/zend_project/public/forum_images");
                    $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $forum_info["name"].$forum_info["cat_id"].'.'.$ext)));                  
                    $upload->receive();
                    $forum_info["image"]=$forum_info["name"].$forum_info["cat_id"].'.'.$ext;
                    $forum_model->addForum($forum_info);
                    
//                    try 
//                    {   
//                        $upload->receive();
//                            
//                        Zend_Debug::dump($upload->getFileInfo());
//                    } catch (Zend_File_Transfer_Exception $e) {
//                            echo $e->message();
//                            }
               }       
           }
       }
       
	$this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        if(!empty($id)){
            $img = $this->_request->getParam("img");
            $catId = $this->_request->getParam("catgId");
            unlink("/var/www/html/zend_project/public/forum_images/$img");
            $forum_model = new Application_Model_Forums();
            $forum_model->deleteForum($id);
        }
       
          $this->redirect("forums/list/id/$catId");
      
    }

    public function editAction()
    {
        $id = $this->_request->getParam("id");
        $form  = new Application_Form_Forum(); 
        $form->getElement("image")->setRequired(false);
        $form->removeElement("cat_id");
        $form->removeElement("is_locked");
        $this->view->form = $form;

        if($this->_request->isPost())
        {
           if($form->isValid($this->_request->getParams()))
            {
               $forum_info = $form->getValues();
               $forum_model = new Application_Model_Forums();
               if($forum_info["image"] !="")
               {
                    $forum_model = new Application_Model_Forums();
                    $forum = $forum_model->getForumById($id);
                  
                    $imgName= $forum[0]['image'];
                    unlink("/var/www/html/zend_project/public/forum_images/$imgName");
                    $ext = pathinfo($forum_info["image"], PATHINFO_EXTENSION);
                    $upload = new Zend_File_Transfer_Adapter_Http();  
                    $upload->setDestination("/var/www/html/zend_project/public/forum_images");
                    $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $forum_info["name"].$forum[0]["cat_id"].'.'.$ext)));                  
                    $upload->receive();
                    $forum_info["image"]=$forum_info["name"].$forum[0]["cat_id"].'.'.$ext;
               }
               $forum_model->editForum ($forum_info); 
                $catId = $this->_request->getParam("catgId");
            $this->redirect("forums/list/id/$catId");
           }
        }
        if (!empty($id)) 
        {
            $forum_model = new Application_Model_Forums();
            $forum = $forum_model->getForumById($id);
            $form->populate($forum[0]);
        } 
        else 
        {
            $catId = $this->_request->getParam("catgId");
            $this->redirect("forums/list/id/$catId");
        }  
        
    
	$this->render('add');
    }

    public function listAction()
    {
        $forum_model = new Application_Model_Forums();
        $id = $this->_request->getParam("id");
        $this->view->forums = $forum_model->getForumsByCategoryId($id);
    }
    
    public function lockAction()
    {
        $forum_model = new Application_Model_Forums();
        $id = $this->_request->getParam("id");
        $lock = $this->_request->getParam("lock");
        $catId = $this->_request->getParam("catgId");
        $this->view->forums = $forum_model->lockForum($id,$lock);
         $this->redirect("forums/list/id/$catId");
    }


}

