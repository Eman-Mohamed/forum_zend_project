<?php

class ThreadsController extends Zend_Controller_Action
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

    public function listAction()
    {
        $thread_model = new Application_Model_Threads();
        $id = $this->_request->getParam("id"); 
        $this->view->threads = $thread_model->getThreadsByForumId($id);
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        if(!empty($id)){
            $forumId = $this->_request->getParam("forumId");
            $thread_model = new Application_Model_Threads();
            $thread_model->deleteThread($id);
        }
      
          $this->redirect("threads/list/id/$forumId");
      
    }

    public function lockAction()
    {
        $thread_model = new Application_Model_Threads();
        $id = $this->_request->getParam("id");
        $lock = $this->_request->getParam("lock");
        $formId = $this->_request->getParam("formId");
        $this->view->forums = $thread_model->lockthread($id,$lock);
        $this->redirect("threads/list/id/$formId");
    }

    public function stickyAction()
    {
        $thread_model = new Application_Model_Threads();
        $id = $this->_request->getParam("id");
        $formId = $this->_request->getParam("formId");
        $this->view->forums = $thread_model->stickthread($id);
        $this->redirect("threads/list/id/$formId");
    }

    public function viewThreadAction()
    {
        $id = $this->_request->getParam("id");
        if (!empty($id)) {
            $thread_model = new Application_Model_Threads();
            $thread = $thread_model->getThreadById($id);
            $reply_model = new Application_Model_Replies();
            $replies = $reply_model->getRepliesByThreadId($id);
        }


        $this->view->thread = $thread[0];
        $this->view->replies = $replies;
    }

    public function addThreadAction()
    {
        $forum_id = $this->getRequest()->getParam("forum_id");
        $form = new Application_Form_Thread();

        if ($this->_request->isPost() && $forum_id) {
            if ($form->isValid($this->_request->getParams())) {
                $thread_info = $form->getValues();
                $thread_info["forum_id"] = $forum_id;
                $thread_info["user_id"] = 9;
                if ($thread_info["image"] != NULL) {
                    $ext = pathinfo($thread_info["image"], PATHINFO_EXTENSION);
                    $string = rand();
                    $arr = range('a', 'z');
                    $x = rand(0, sizeof($arr) - 1);
                    $y = rand(0, sizeof($arr) - 1);
                    $z = rand(0, sizeof($arr) - 1);
                    $string .= $arr[$x] . $arr[$y] . $arr[$z] . '.' . $ext;
                    $ext = pathinfo($thread_info["image"], PATHINFO_EXTENSION);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination("/var/www/html/zend_project/public/thread_images");
                    $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $string)));
                    $upload->receive();
                    

                    $thread_info["image"] = $string;
                }
//                var_dump($thread_info);
                $thread_model = new Application_Model_Threads();
               $thread_model->addThread($thread_info);
            }
        }

        $this->view->form = $form;
 
    }

    public function editAction()
    {
        $id = $this->_request->getParam("id");
        $form = new Application_Form_Thread();
        $form->getElement("submit")->setLabel("Done");
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getParams())) {
                $thread_info = $form->getValues();
                if ($thread_info["image"] != NULL) {
                    $ext = pathinfo($thread_info["image"], PATHINFO_EXTENSION);
                    $string = rand();
                    $arr = range('a', 'z');
                    $x = rand(0, sizeof($arr) - 1);
                    $y = rand(0, sizeof($arr) - 1);
                    $z = rand(0, sizeof($arr) - 1);
                    $string .= $arr[$x] . $arr[$y] . $arr[$z] . '.' . $ext;
                    $ext = pathinfo($thread_info["image"], PATHINFO_EXTENSION);
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination("/var/www/html/zend_project/public/thread_images");
                    $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $string)));
                    $upload->receive();
                    

                    $thread_info["image"] = $string;
                }else{
                    $thread_info["image"] = $thread[0]["image"];
                }
                var_dump($thread_info);
                $thread_model = new Application_Model_Threads();
                $thread_model->editThread($thread_info);
                $this->redirect("threads/view-thread/id/$id");
            }
            if (!empty($id)) {
                $thread_info = $form->getValues();
                $form->populate($thread_info);
            } else
                $this->redirect("threads/view-thread/id/$id");
        }else {
            $thread_model = new Application_Model_Threads();
            $thread = $thread_model->getThreadById($id);
            $form->populate($thread[0]);
        }
        $this->view->form = $form;
    }

    


}







