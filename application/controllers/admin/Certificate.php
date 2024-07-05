<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificate extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('media_storage');
        $this->load->library('Customlib');
        $this->load->model('certificate_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_certificate', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/certificate');

        $this->form_validation->set_rules('certificate_name', $this->lang->line('certificate_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('certificate_text', $this->lang->line('certificate_text'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('background_image', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {

            $this->data['certificateList'] = $this->certificate_model->certificateList();
            $this->load->view('layout/header');
            $this->load->view('admin/certificate/createcertificate', $this->data);
            $this->load->view('layout/footer');
        } else {
            if ($this->input->post('is_active_student_img') == 1) {
                $enableimg = $this->input->post('is_active_student_img');
                $imgHeight = $this->input->post('image_height');
            } else {
                $enableimg = 0;
                $imgHeight = 0;
            } 

            $picture = $this->media_storage->fileupload("background_image", "./uploads/certificate/");

            $data = array(
                'certificate_name'     => $this->input->post('certificate_name'),
                'certificate_text'     => $this->input->post('certificate_text'),
                'left_header'          => $this->input->post('left_header'),
                'center_header'        => $this->input->post('center_header'),
                'right_header'         => $this->input->post('right_header'),
                'left_footer'          => $this->input->post('left_footer'),
                'right_footer'         => $this->input->post('right_footer'),
                'center_footer'        => $this->input->post('center_footer'),
                'created_for'          => 2,
                'status'               => 1,
                'background_image'     => $picture,
                'header_height'        => $this->input->post('header_height'),
                'content_height'       => $this->input->post('content_height'),
                'footer_height'        => $this->input->post('footer_height'),
                'content_width'        => $this->input->post('content_width'),
                'enable_student_image' => $enableimg,
                'enable_image_height'  => $imgHeight,
            );

            $this->certificate_model->addcertificate($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/certificate');
        }
    }

    public function edit($id)
    {

        if (!$this->rbac->hasPrivilege('student_certificate', 'can_edit')) {
            access_denied();
        }
        $data['title']                 = 'Add Hostel';
        $data['id']                    = $id;
        $editcertificate               = $this->certificate_model->get($id);
        $this->data['editcertificate'] = $editcertificate;

        $custom_fields               = $this->customfield_model->get_custom_fields('students');
        $this->data['custom_fields'] = $custom_fields;
        $this->form_validation->set_rules('certificate_name', $this->lang->line('certificate_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('certificate_text', $this->lang->line('certificate_text'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('background_image', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {
            $this->data['certificateList'] = $this->certificate_model->certificateList();
            $this->load->view('layout/header');
            $this->load->view('admin/certificate/studentcertificateedit', $this->data);
            $this->load->view('layout/footer');
        } else {

            if ($this->input->post('is_active_student_img') == 1) {
                $enableimg = $this->input->post('is_active_student_img');
                $imgHeight = $this->input->post('image_height');
            } else {
                $enableimg = 0;
                $imgHeight = 0;
            }

            $data = array(
                'id'                   => $this->input->post('id'),
                'certificate_name'     => $this->input->post('certificate_name'),
                'certificate_text'     => $this->input->post('certificate_text'),
                'left_header'          => $this->input->post('left_header'),
                'center_header'        => $this->input->post('center_header'),
                'right_header'         => $this->input->post('right_header'),
                'left_footer'          => $this->input->post('left_footer'),
                'right_footer'         => $this->input->post('right_footer'),
                'center_footer'        => $this->input->post('center_footer'),
                'header_height'        => $this->input->post('header_height'),
                'content_height'       => $this->input->post('content_height'),
                'footer_height'        => $this->input->post('footer_height'),
                'content_width'        => $this->input->post('content_width'),
                'enable_student_image' => $enableimg,
                'enable_image_height'  => $imgHeight,
            );

            if (!empty($_FILES['background_image']['name'])) {
                $img_name = $this->media_storage->fileupload("background_image", "./uploads/certificate/");

                $data['created_for']      = 2;
                $data['status']           = 1;
                $data['background_image'] = $img_name;

                if ($editcertificate[0]->background_image != '') {
                    $this->media_storage->filedelete($editcertificate[0]->background_image, "uploads/certificate/");
                }
            }

            $removebackground_image = $this->input->post('removebackground_image');

            if ($removebackground_image != '') {
                $data['background_image'] = '';
            }

            $this->certificate_model->addcertificate($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/certificate/index');
        }
    } 

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_certificate', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Certificate List';
        $row           = $this->certificate_model->get($id);
        if ($row[0]->background_image != '') {
            $this->media_storage->filedelete($row[0]->background_image, "uploads/certificate/");
        }

        $this->certificate_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/certificate/index');
    }

    public function view()
    {
        $id     = $this->input->post('certificateid');
        $output = '';
        $data   = array();

        $data['certificate'] = $this->certificate_model->certifiatebyid($id);
        $preview             = $this->load->view('admin/certificate/preview_certificate', $data, true);
        echo $preview;
    }

    public function view1()
    {
        $id          = $this->input->post('certificateid');
        $output      = '';
        $certificate = $this->certificate_model->certifiatebyid($id);
        ?>
        <style type="text/css">
            body{ font-family: 'arial';}
            .tc-container{width: 100%;position: relative; text-align: center;}
            .tc-container tr td{vertical-align: bottom;}
        </style>
        <div class="tc-container">
            <img src="<?php echo base_url('uploads/certificate/') ?><?php echo $certificate->background_image; ?>" width="100%" height="100%" />
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificate->content_width; ?>px; top:<?php echo $certificate->enable_image_height; ?>px">
                    <td  valign="top" style="position: absolute;right: 0;">
                        <?php if ($certificate->enable_student_image == 1) {?>
                            <img src="<?php echo base_url('uploads/certificate/noimage.jpg') ?>" width="100" height="auto">
                        <?php }?>
                    </td>
                </tr>
                <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificate->content_width; ?>px; top:<?php echo $certificate->header_height; ?>px">
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size: 18px; text-align:left;position:relative;"><?php echo $certificate->left_header; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size: 18px; text-align:center; position:relative; "><?php echo $certificate->center_header; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size: 18px; text-align:right;position:relative;"><?php echo $certificate->right_header; ?></td>
                </tr>
                <tr style="position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0; width:<?php echo $certificate->content_width; ?>px; display: block; top:<?php echo $certificate->content_height; ?>px;">
                    <td colspan="3" valign="top" align="center"><p style="font-size: 16px;position: relative;text-align:center; margin:0 auto; width: 100%; left:auto; right:0;"><?php echo $certificate->certificate_text; ?></p>
                    </td>
                </tr>
                <tr style="position:absolute; margin-left: auto;margin-right: auto;left: 0;right: 0;  width:<?php echo $certificate->content_width; ?>px; top:<?php echo $certificate->footer_height; ?>px">
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px; font-size:18px;text-align:left;"><?php echo $certificate->left_footer; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px; font-size:18px;text-align:center;"><?php echo $certificate->center_footer; ?></td>
                    <td valign="top" style="width:<?php echo $certificate->content_width; ?>px;font-size:18px;text-align:right;"><?php echo $certificate->right_footer; ?></td>
                </tr>
            </table>
        </div>
        <?php
}

    public function handle_upload()
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["background_image"]) && !empty($_FILES['background_image']['name']) && $_FILES["background_image"]["size"] > 0) {

            $file_type = $_FILES["background_image"]['type'];
            $file_size = $_FILES["background_image"]["size"];
            $file_name = $_FILES["background_image"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['background_image']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            return true;
        }

    }

}
?>