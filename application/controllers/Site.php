<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Site extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->check_installation();
        if ($this->config->item('installed') == true) {
            $this->db->reconnect();
        }

        $this->load->model(array("staff_model", "sharecontent_model"));
        $this->load->library('Auth');
        $this->load->library('Enc_lib');
        $this->load->library('customlib');
        $this->load->library('captchalib');
        $this->load->library('mailsmsconf');
        $this->load->library('mailer');
        $this->load->library('media_storage');
        $this->load->config('ci-blog');
        $this->mailer;
        $this->sch_setting = $this->setting_model->getSetting();
    }

    private function check_installation()
    {
        if ($this->uri->segment(1) !== 'install') {
            $this->load->config('migration');
            if ($this->config->item('installed') == false && $this->config->item('migration_enabled') == false) {
                redirect(base_url() . 'install/start');
            } else {
                if (is_dir(APPPATH . 'controllers/install')) {
                    echo '<h3>Delete the install folder from application/controllers/install</h3>';
                    die;
                }
            }
        }
    }

    public function login()
    {
        $app_name = $this->setting_model->get();
        $app_name = $app_name[0]['name'];

        if ($this->auth->logged_in()) {
            $this->auth->is_logged_in(true);
        }
        
        if ($this->module_lib->hasModule('google_authenticator') 
            && $this->module_lib->hasActive('google_authenticator')) {

            redirect('gauthenticate/login');
     
        }	
        
        $data          = array();
        $data['title'] = 'Login';
        $school        = $this->setting_model->get();

        $data['name'] = $app_name;

        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $school[0];
        $is_captcha         = $this->captchalib->is_captcha('login');
        $data["is_captcha"] = $is_captcha;
        if ($this->captchalib->is_captcha('login')) {
            if($this->input->post('captcha')){
                $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required|callback_check_captcha');
            }else{
                $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required');
            }
        }
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $captcha               = $this->captchalib->generate_captcha();
            $data['captcha_image'] = isset($captcha['image']) ? $captcha['image'] : "";
            $data['name']          = $app_name;
            $this->load->view('admin/login', $data);
        } else {
            $login_post = array(
                'email'    => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            if ($this->captchalib->is_captcha('login')) {
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            }
            $setting_result        = $this->setting_model->get();
            $result                = $this->staff_model->checkLogin($login_post);           
           
            if (!empty($result->language_id)) {
                $lang_array = array('lang_id' => $result->language_id, 'language' => $result->language);
                if ($result->is_rtl == 1) {
                    $is_rtl = "enabled";
                } else {
                    $is_rtl = "disabled";
                }

            } else {
                $lang_array = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                if ($setting_result[0]['is_rtl'] == 1) {
                    $is_rtl = "enabled";
                } else {
                    $is_rtl = "disabled";
                }
            }

            if ($result) {
                if ($result->is_active) {
                    if ($result->surname != "") {
                        $logusername = $result->name . " " . $result->surname;
                    } else {
                        $logusername = $result->name;
                    }

                    $session_data = array(
                        'id'                     => $result->id,
                        'username'               => $logusername,
                        'email'                  => $result->email,
                        'image'                  =>$result->image,
                        'roles'                  => $result->roles,
                        'date_format'            => $setting_result[0]['date_format'],                        
                        'currency'               => ($result->currency == 0) ? $setting_result[0]['currency']: $result->currency,
                        'currency_base_price'    => ($result->base_price == 0) ? $setting_result[0]['base_price']: $result->base_price,
                        'currency_format'        => $setting_result[0]['currency_format'],
                        'currency_symbol'        => ($result->symbol == "0") ? $setting_result[0]['currency_symbol'] : $result->symbol,
                        'currency_place'         => $setting_result[0]['currency_place'],
                        'start_month'            => $setting_result[0]['start_month'],
                        'start_week'             => date("w", strtotime($setting_result[0]['start_week'])),
                        'school_name'            => $setting_result[0]['name'],
                        'timezone'               => $setting_result[0]['timezone'],
                        'sch_name'               => $setting_result[0]['name'],
                        'language'               => $lang_array,
                        'is_rtl'                 => $is_rtl,
                        'theme'                  => $setting_result[0]['theme'],
                        'gender'                 => $result->gender,                     
                        'db_array'               => ['base_url'               => $setting_result[0]['base_url'],
                                                     'folder_path'            => $setting_result[0]['folder_path'],
                                                     'db_group'=>'default'
                                                    ],
                        'superadmin_restriction' => $setting_result[0]['superadmin_restriction'],
                    );

                    $this->session->set_userdata('admin', $session_data);

                    $role      = $this->customlib->getStaffRole();
                    $role_name = json_decode($role)->name;
                    $this->customlib->setUserLog($this->input->post('username'), $role_name);

                    if (isset($_SESSION['redirect_to'])) {
                        redirect($_SESSION['redirect_to']);
                    } else {
                        redirect('admin/admin/dashboard');
                    }

                } else {
                    $data['name']          = $app_name;
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');

                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['name']          = $app_name;
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('admin/login', $data);
            }
        }
    }

    public function logout()
    {
        $admin_session   = $this->session->userdata('admin');
        $student_session = $this->session->userdata('student');
        $this->auth->logout();
        if ($admin_session) {
            redirect('site/login');
        } else if ($student_session) {
            redirect('site/userlogin');
        } else {
            redirect('site/userlogin');
        }
    }

    public function download_content($share_id, $content_id)
    {
        $content_id = $this->enc_lib->dycrypt($content_id);
        $content    = $this->sharecontent_model->checkvalid($share_id, $content_id);
        if ($content) {
            $this->media_storage->filedownload($content->img_name, $content->dir_path);
        } else {
            echo $this->lang->line('invalid_or_expired_link_please_check_it_again');
        }
    }

    public function forgotpassword()
    {
       
        $app_name     = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|valid_email|required|xss_clean');
        
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $app_name[0];
         
        if ($this->form_validation->run() == false) {
            $this->load->view('admin/forgotpassword', $data);
        } else {
            $email = $this->input->post('email');

            $result = $this->staff_model->getByEmail($email);

            if ($result && $result->email != "") {
                if ($result->is_active == '1') {
                    $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                    $update_record     = array('id' => $result->id, 'verification_code' => $verification_code);
                    $this->staff_model->add($update_record);
                    $name           = $result->name;
                    $resetPassLink  = site_url('admin/resetpassword') . "/" . $verification_code;
                    $sender_details = array('resetPassLink' => $resetPassLink, 'name' => $name, 'username' => $result->surname, 'staff_email' => $email);
                    $this->mailsmsconf->mailsms('forgot_password', $sender_details);
                    $this->session->set_flashdata('message', $this->lang->line('please_check_your_email_to_recover_your_password'));
                } else {
                    $this->session->set_flashdata('disable_message', $this->lang->line('your_account_is_disabled_please_contact_to_administrator'));
                }

                redirect('site/login', 'refresh');
            } else {

                $data['error_message'] = $this->lang->line('incorrect_email');
                
            }
            
            $this->load->view('admin/forgotpassword', $data);
        }
    }

    //reset password - final step for forgotten password
    public function admin_resetpassword($verification_code = null)
    {
        $app_name     = $this->setting_model->get();
        $data['name'] = $app_name[0]['name'];
        $data['admin_login_page_background'] = $app_name[0]['admin_login_page_background'];
        if (!$verification_code) {
            show_404();
        }

        $user = $this->staff_model->getByVerificationCode($verification_code);
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        
        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {
                
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('admin/admin_resetpassword', $data);
            } else {

                // finally change the password
                $password      = $this->input->post('password');
                $update_record = array(
                    'id'                => $user->id,
                    'password'          => $this->enc_lib->passHashEnc($password),
                    'verification_code' => "",
                );

                $change = $this->staff_model->update($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line("password_reset_successfully"));
                    redirect('site/login', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line("something_went_wrong"));
                    redirect('admin_resetpassword/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/forgotpassword", 'refresh');
        }
    }
    
    //reset password - final step for forgotten password
    public function share($key)
    {
        $data               = array();
        $id                 = $this->enc_lib->dycrypt($key);
        $data['share_data'] = $this->sharecontent_model->getShareContentWithDocuments($id);       
        $this->load->view('share', $data);

    }
    
    //reset password - final step for forgotten password
    public function resetpassword($role = null, $verification_code = null)
    {
        $app_name     = $this->setting_model->get();
        $data['app_name'] = $app_name;
        if (!$role || !$verification_code) {
            show_404();
        }
        
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;

        $user = $this->user_model->getUserByCodeUsertype($role, $verification_code);

        if ($user) {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('confirm_password'), 'required|matches[password]');
            if ($this->form_validation->run() == false) {

                $data['role']              = $role;
                $data['verification_code'] = $verification_code;
                //render
                $this->load->view('resetpassword', $data);
            } else {

                // finally change the password

                $update_record = array(
                    'id'                => $user->user_tbl_id,
                    'password'          => $this->input->post('password'),
                    'verification_code' => "",
                );

                $change = $this->user_model->saveNewPass($update_record);
                if ($change) {
                    //if the password was successfully changed
                    $this->session->set_flashdata('message', $this->lang->line('password_reset_successfully'));
                    redirect('site/userlogin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->lang->line("something_went_wrong"));
                    redirect('user/resetpassword/' . $role . '/' . $verification_code, 'refresh');
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->lang->line('invalid_link'));
            redirect("site/ufpassword", 'refresh');
        }
    }

    public function ufpassword()
    {  
        
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices; 
        
        $this->form_validation->set_rules('username', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('user[]', $this->lang->line('user_type'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $this->load->view('ufpassword', $data);
        } else {
            $email    = $this->input->post('username');
            $usertype = $this->input->post('user[]');
            $result   = $this->user_model->forgotPassword($usertype[0], $email);
        
            if ($result && $result->email != "") {

                $verification_code = $this->enc_lib->encrypt(uniqid(mt_rand()));
                $update_record     = array('id' => $result->user_tbl_id, 'verification_code' => $verification_code);
                $this->user_model->updateVerCode($update_record);

                if ($usertype[0] == "student") {
                    $name     = $this->customlib->getFullName($result->firstname, $result->middlename, $result->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname);
                    $username = $result->username;
                } else {
                    $name     = $result->guardian_name;
                    $username = $result->username;
                }

                $resetPassLink  = site_url('user/resetpassword') . '/' . $usertype[0] . "/" . $verification_code;
                $sender_details = array('resetPassLink' => $resetPassLink, 'name' => $name, 'username' => $username);
                if ($usertype[0] == "student") {
                    $sender_details['email'] = $email;
                } else {
                    $sender_details['guardian_email'] = $email;
                }
                $this->mailsmsconf->mailsms('forgot_password', $sender_details);
                $this->session->set_flashdata('message', $this->lang->line("please_check_your_email_to_recover_your_password"));
                redirect('site/userlogin', 'refresh');
            } else {
                $data = array(
                     
                    'error_message' => $this->lang->line('invalid_email_or_user_type'),
                );
            }
            
            $data['notice']     = $notices; 
        
            $this->load->view('ufpassword', $data);
        }
    }

    public function userlogin()
    {
        $school = $this->setting_model->get();

        if (!$school[0]['student_panel_login']) {
            redirect('site/login', 'refresh');
        }

        if ($this->auth->user_logged_in()) {
            $this->auth->user_redirect();
        }
        
        if ($this->module_lib->hasModule('google_authenticator') 
            && $this->module_lib->hasActive('google_authenticator')) {             redirect('gauthenticate/userlogin');     
        }

        $data               = array();
        $data['title']      = 'Login';
        $data['name']       = $school[0]['name'];
        $notice_content     = $this->config->item('ci_front_notice_content');
        $notices            = $this->cms_program_model->getByCategory($notice_content, array('start' => 0, 'limit' => 5));
        $data['notice']     = $notices;
        $data['school']     = $school[0];
        $is_captcha         = $this->captchalib->is_captcha('userlogin');
        $data["is_captcha"] = $is_captcha;
        if ($is_captcha) {
            
            if($this->input->post('captcha')){
                $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required|callback_check_captcha');
            }else{
                $this->form_validation->set_rules('captcha', $this->lang->line('captcha'), 'trim|required');
            }  
            
        }
        $this->form_validation->set_rules('username', $this->lang->line('username'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            if ($this->captchalib->is_captcha('userlogin')) {
                $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            }
            $this->load->view('userlogin', $data);
        } else {
            $login_post = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
            );
            $data['captcha_image'] = $this->captchalib->generate_captcha()['image'];
            $login_details         = $this->user_model->checkLogin($login_post);

            if (isset($login_details) && !empty($login_details)) {
                $user = $login_details[0];

                if ($user->is_active == "yes") {
                    if ($user->role == "student") {
                        $result = $this->user_model->read_user_information($user->id);

                    } else if ($user->role == "parent") {
                        if ($school[0]['parent_panel_login']) {
                            $result = $this->user_model->checkLoginParent($login_post);


                        } else {
                            $result = false;

                        }
                    } 

                    if ($result != false) {
                        $setting_result = $this->setting_model->get();
                        if ($result[0]->lang_id == 0) {
                            $language = array('lang_id' => $setting_result[0]['lang_id'], 'language' => $setting_result[0]['language']);
                            if ($setting_result[0]['is_rtl'] == 1) {
                                $is_rtl = "enabled";
                            } else {
                                $is_rtl = "disabled";
                            }
                        } else {
                            $language = array('lang_id' => $result[0]->lang_id, 'language' => $result[0]->language);
                            if ($setting_result[0]['is_rtl'] == 1) {
                                $is_rtl = "enabled";
                            } else {
                                $is_rtl = "disabled";
                            }
                        }
                        $image = '';
                        if ($result[0]->role == "parent") {
                            $username = $result[0]->guardian_name;
                            if ($result[0]->guardian_is == "father") {
                                $image = $result[0]->father_pic;
                            } else if ($result[0]->guardian_is == "mother") {
                                $image = $result[0]->mother_pic;
                            } else if ($result[0]->guardian_is == "other") {
                                $image = $result[0]->guardian_pic;
                            }
                        } elseif ($result[0]->role == "student") {
                            $image        = $result[0]->image;
                            $username     = $this->customlib->getFullName($result[0]->firstname, $result[0]->middlename, $result[0]->lastname, $this->sch_setting->middlename, $this->sch_setting->lastname);
                            $defaultclass = $this->user_model->get_studentdefaultClass($result[0]->user_id);
                            $this->customlib->setUserLog($result[0]->username, $result[0]->role, $defaultclass['id']);
                        }

                        $session_data = array(
                            'id'                     => $result[0]->id,
                            'login_username'         => $result[0]->username,
                            'student_id'             => $result[0]->user_id,
                            'role'                   => $result[0]->role,
                            'username'               => $username,
                            'currency'               => ( $result[0]->currency == 0) ? $setting_result[0]['currency_id']:  $result[0]->currency,
                            'currency_base_price'    => ( $result[0]->base_price == 0) ? $setting_result[0]['base_price']:  $result[0]->base_price,
                            'currency_format'        => $setting_result[0]['currency_format'],
                            'currency_symbol'        => ($result[0]->symbol == "0") ? $setting_result[0]['currency_symbol'] : $result[0]->symbol,
                            'currency_name'          => ($result[0]->currency_name == "0") ? $setting_result[0]['currency'] : $result[0]->currency_name,
                            'currency_place'         => $setting_result[0]['currency_place'],
                            'date_format'            => $setting_result[0]['date_format'],
                            'start_week'             => date("w", strtotime($setting_result[0]['start_week'])),
                            'timezone'               => $setting_result[0]['timezone'],
                            'sch_name'               => $setting_result[0]['name'],
                            'language'               => $language,
                            'is_rtl'                 => $is_rtl,
                            'theme'                  => $setting_result[0]['theme'],
                            'image'                  => $image,
                            'gender'                 => $result[0]->gender,
                            'db_array'               => ['base_url'           => $setting_result[0]['base_url'],
                                                     'folder_path'            => $setting_result[0]['folder_path'],
                                                     'db_group'=>'default'
                                                    ],
                            'superadmin_restriction' => $setting_result[0]['superadmin_restriction'],

                        );

                        $this->session->set_userdata('student', $session_data);
                        if ($result[0]->role == "parent") {
                            $this->customlib->setUserLog($result[0]->username, $result[0]->role);
                        }
                        redirect('user/user/choose');
                    } else {
                        $data['error_message'] = $this->lang->line('account_suspended');
                        $this->load->view('userlogin', $data);
                    }
                } else {
                    $data['error_message'] = $this->lang->line('your_account_is_disabled_please_contact_to_administrator');
                    $this->load->view('userlogin', $data);
                }
            } else {
                $data['error_message'] = $this->lang->line('invalid_username_or_password');
                $this->load->view('userlogin', $data);
            }
        }
    }

    public function savemulticlass()
    {
        $student_id = '';
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student_id' => form_error('student_id'),
            );

            $array = array('status' => '0', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'student_id' => date('Y-m-d', strtotime($this->input->post('student_id'))),
            );

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function check_captcha($captcha)
    {
        if ($captcha != $this->session->userdata('captchaCode')):
            $this->form_validation->set_message('check_captcha', $this->lang->line('incorrect_captcha'));
            return false;
        else:
            return true;
        endif;
    }

    public function refreshCaptcha()
    {
        $captcha = $this->captchalib->generate_captcha();
        echo $captcha['image'];
    }

}
