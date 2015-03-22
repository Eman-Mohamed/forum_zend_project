<?php

class CategoriesController extends Zend_Controller_Action
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
        $form  = new Application_Form_Category();
       
       if($this->_request->isPost()){
            if($form->isValid($this->_request->getParams()))
            {
                $category_info = $form->getValues();
             
                $category_model = new Application_Model_Categories();
   
                $ext = pathinfo($category_info["image"], PATHINFO_EXTENSION);
                $upload = new Zend_File_Transfer_Adapter_Http();  
                $upload->setDestination("/var/www/html/zend_project/public/category_images");
                $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $category_info["name"].'.'.$ext)));                  
                $upload->receive();
                $category_info["image"]=$category_info["name"].'.'.$ext;
                $category_model->addCategory($category_info);

               }       
           }
       
       
	$this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        $d= $this->_request->getParam("d");
        if(!empty($id)){
            $imgCat = $this->_request->getParam("catImg");
            unlink("/var/www/html/zend_project/public/category_images/$imgCat");
            $forum_model = new Application_Model_Forums();
             $delImg=$forum_model->getForumsByCategoryId($id);
             
//            unlink("/var/www/html/zend_project/public/forums_images/$imgCat");
//            unlink("/var/www/html/zend_project/public/threads_images/$imgCat");
            $Category_model = new Application_Model_Categories();
            $Category_model->deleteCategory($id);
        }
       
        if($d ==-1)
        {
        
            $this->redirect("categories/main");
        }
        if($d ==-2)
        {
            $this->redirect("categories/list");
        }
      
    }

    public function editAction()
    {
        $id = $this->_request->getParam("id");
        $e= $this->_request->getParam("e");
        $form  = new Application_Form_Category(); 
        $form->getElement("image")->setRequired(false);
        $form->getElement("name")->removeValidator("Db_NoRecordExists");
        $this->view->form = $form;

        if($this->_request->isPost())
        {
           if($form->isValid($this->_request->getParams()))
            {
               $category_info = $form->getValues();
               $category_model = new Application_Model_Categories();
               if($category_info["image"] !="")
               {
                    $category_model = new Application_Model_Categories();
                    $forum = $category_model->getCategoryById($id);
                  
                    $imgName= $forum[0]['image'];
                    unlink("/var/www/html/zend_project/public/category_images/$imgName");
                    $ext = pathinfo($category_info["image"], PATHINFO_EXTENSION);
                    $upload = new Zend_File_Transfer_Adapter_Http();  
                    $upload->setDestination("/var/www/html/zend_project/public/category_images");
                    $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $category_info["name"].'.'.$ext)));                  
                    $upload->receive();
                    $category_info["image"]=$category_info["name"].'.'.$ext;
               }
               $category_model->editCategory($category_info); 
               if($e == -1)
               {
               $this->redirect("categories/main");
               }
               if($e == -2)
               {
               $this->redirect("categories/list");
               }
               
           }
        }
        if (!empty($id)) 
        {
            $category_model = new Application_Model_Categories();
            $forum = $category_model->getCategoryById($id);
            $form->populate($forum[0]);
        } 
        else 
        {
            if($e ==-1)
               {
               $this->redirect("categories/main");
               }
               if($e ==-2)
               {
               $this->redirect("categories/list");
               }
        }  
        
    
	$this->render('add');
    }

    public function listAction()
    {
        $category_model = new Application_Model_Categories();
        $this->view->categories = $category_model->listCategory();
    }
    
      public function mainAction()
    {
       $category_model = new Application_Model_Categories();
        $this->view->categories = $category_model->listCategory();
        $forum_model = new Application_Model_Forums();
        $this->view->forums = $forum_model->listForum();
    }
    
    public function lockforumAction()
    {
        $forum_model = new Application_Model_Forums();
        $id = $this->_request->getParam("id");
        $lock = $this->_request->getParam("lock");
        $forum_model->lockForum($id,$lock);
         $this->redirect("categories/main");
    }
    
    public function deleteforumAction()
    {
        $id = $this->_request->getParam("id");
        if(!empty($id)){
            $img = $this->_request->getParam("img");
            unlink("/var/www/html/zend_project/public/forum_images/$img");
            $forum_model = new Application_Model_Forums();
            $forum_model->deleteForum($id);
        }
       
          $this->redirect("categories/main");
      
    }

    public function editforumAction()
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
            
             $this->redirect("categories/main");
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
           $this->redirect("categories/main");
        }  
        
    
	$this->render('add');
    }


}

