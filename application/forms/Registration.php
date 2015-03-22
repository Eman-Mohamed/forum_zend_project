<?php

class Application_Form_Registration extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $id = $this->createElement('hidden','id');
        $name = $this->createElement('text','name');
        $name->setLabel('Name:')
                    ->setRequired(true)
                    ->addFilter('StripTags')
                    ->addFilter('StripTags');
//        $name->setAttrib("class", "form-control");
//       $name->setAttrib("placeholder", "Name");
        
        $email = $this->createElement('text','email');
        $email->setLabel('Email: *')
                ->setRequired(true);
//        $email->setAttrib("class", "form-control");
//       $email->setAttrib("placeholder", "Email");
                
        
                
        $password = $this->createElement('password','password');
        $password->setLabel('Password: *')
                ->setRequired(true);
//        $password->setAttrib("class", "form-control");
//        $password->setAttrib("placeholder", "Password");
        
        $gender =new Zend_Form_Element_Radio("gender");
        $gender->addFilter(new Zend_Filter_StringTrim())
        ->setMultiOptions(array('male'=>'Male', 'female'=>'Female'))
        ->setAttrib("name", "gender")
        ->setRequired(true)
        ->setDecorators(array( array('ViewHelper') ));
       
       
        
        $image = new Zend_File_Transfer_Adapter_Http();
        $image = new Zend_Form_Element_File('image');
        $image->setLabel("Upload Image ")
            ->setRequired(true)               
            ->addValidator('Extension',false,'jpg,png,gif,jpeg')
            ->setDestination("../public/profile_images")
            ->addValidator('Count',false,1) //ensure only 1 file
            ->addValidator('Size',false,102400*100) //limit to 100K
            ->getValidator('Extension')->setMessage('This file type is not supportted.');
        
        $country=new Zend_Form_Element_Select("country");
         $country->setLabel("Country ");
         $locale=new Zend_Locale("en_US");
         $countries=$locale->getTranslationList('Territory', 'en_US', 2);
         asort($countries,SORT_LOCALE_STRING );
         $country->setMultiOptions($countries);
         $country->setRequired(true);
        
        
        $signature = new Zend_Form_Element_File('signature');
        $signature->setLabel("Upload signature ")
            ->setRequired(true)               
            ->addValidator('Extension', false, 'jpeg,png,gif,jpg')
            ->setDestination("../public/signture_images")
            ->addValidator('Count',false,1) //ensure only 1 file
            ->addValidator('Size',false,102400) //limit to 100K   
                
            ->getValidator('Extension')->setMessage('This file type is not supportted.');
         

        
                
        $register = $this->createElement('submit','register');
        $register->setLabel('Sign up');
        $register->setAttrib("class", "btn btn-primary")
                                ->setIgnore(true);
                
                
        $this->addElements(array(
                        $name,
                        $email,
                        $password,
                        $gender,
                        $country,
                        $image,
                        $signature,
                        $register,
                        $id
        ));
    }


}

