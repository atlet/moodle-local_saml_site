<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once $CFG->libdir . '/formslib.php';

class local_saml_site_addnewrule_form extends moodleform {

    function definition() {
        global $CFG, $DB;
        
        $rulestype = array();
        $rulestype[1] = get_string('usernamedomainname', 'local_saml_site'); 
        $rulestype[2] = get_string('customfield', 'local_saml_site');
        
        $customFieldsDB = $DB->get_records("user_info_field", null, null, 'name, shortname');
        
        $customFields = array();
        foreach ($customFieldsDB as $value) {
            $customFields[$value->shortname] = $value->name;
        }
        
        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('select', 'ruletype', get_string('ruletype', 'local_saml_site'), $rulestype);
        $mform->setDefault('ruletype', 1);
        $mform->addRule('ruletype', null, 'required', null, 'client');
        
        $mform->addElement('select', 'customprofilefield', get_string('customprofilefield', 'local_saml_site'), $customFields);
        $mform->disabledIf('customprofilefield', 'ruletype', 'neq', 2);
        
        $mform->addElement('text', 'rule', get_string('rule', 'local_saml_site'), array('size' => '64'));
        $mform->addRule('rule', null, 'required', null, 'client');
        $mform->setType('rule', PARAM_TEXT);

        $mform->addElement('hidden', 'id');
        $mform->addElement('hidden', 'local_saml_site_id');
        $mform->setType('id', PARAM_RAW);
        $mform->setType('local_saml_site_id', PARAM_INT);
        $mform->setDefault('local_saml_site_id', $this->_customdata['cid']);

        $this->add_action_buttons();
    }

}
