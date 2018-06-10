<?php
// Copyright 1999-2018. Plesk International GmbH.

/**
 * Class Modules_WelcomeBusiness_ContentInclude
 */
class Modules_WelcomeBusiness_ContentInclude extends pm_Hook_ContentInclude
{
    public function init()
    {
        if (pm_Session::isExist()) {

            if (pm_Session::getClient()->isAdmin()) {
                $status = pm_Settings::get('active', 1);

                if (!empty($status)) {
                    $head = new Zend_View_Helper_HeadLink();
                    $head->headLink()->appendStylesheet(pm_Context::getBaseUrl() . 'styles.css');

                    $page_loaded = Modules_WelcomeBusiness_Helper::getLoadedPage();
                    $white_list = Modules_WelcomeBusiness_Helper::getWhiteListPages();

                    if (Modules_WelcomeBusiness_Helper::addMessage()) {
                        if (in_array($page_loaded, $white_list)) {
                            $client_name = pm_Session::getClient()->getProperty('pname');

                            if (empty($client_name)) {
                                $client_name = pm_Session::getClient()->getProperty('login');
                            }

                            $content = pm_Locale::lmsg('message_introtext', [
                                'close'      => '/modules/welcome-business/images/close.png',
                                'close_link' => pm_Context::getActionUrl('index', 'deactivate'),
                                'elvis'      => '/modules/welcome-business/images/plesk_octopus_business' . mt_rand(1, 2) . '.png',
                                'name'       => $client_name
                            ]);

                            if (Modules_WelcomeBusiness_Helper::checkAvailableDomains() == false) {
                                $content .= pm_Locale::lmsg('message_step_domain', [
                                    'link_domain' => pm_Context::getActionUrl('index', 'redirectnewdomain')
                                ]);
                            } else {
                                $white_list_os = Modules_WelcomeBusiness_Helper::stepListOs();
                                $step = pm_Settings::get('welcome-step', 1);

                                if ($step == 1) {
                                    if (Modules_WelcomeBusiness_Helper::isInstalled('wp-toolkit')) {
                                        if (Modules_WelcomeBusiness_Helper::isInstalled('site-import')) {
                                            $content .= pm_Locale::lmsg('message_step_install_full', [
                                                'link_install' => pm_Context::getActionUrl('index', 'redirect-custom-wp-install'),
                                                'link_migrate' => '/modules/site-import/index.php/site-migration/new-migration'
                                            ]);
                                        } else {
                                            $content .= pm_Locale::lmsg('message_step_install_new', [
                                                'link_install'          => pm_Context::getActionUrl('index', 'redirect-custom-wp-install'),
                                                'link_install_migrator' => pm_Context::getActionUrl('index', 'install') . '?extension=site-import'
                                            ]);
                                        }
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_install_not_wptoolkit', [
                                            'link_install' => Modules_WelcomeBusiness_Helper::getExtensionCatalogLink('wp-toolkit')
                                        ]);
                                    }

                                    if (in_array('kolab', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_kolab_inactive', [
                                            'class' => 'todo'
                                        ]);
                                    }

                                    if (in_array(Modules_WelcomeBusiness_Helper::getAdvisorData(), $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_ssl_inactive', [
                                            'class'             => 'todo',
                                            'link_advisor_name' => Modules_WelcomeBusiness_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (in_array('pagespeed-insights', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_inactive', [
                                            'class' => 'todo'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_next', [
                                        'link_next'       => pm_Context::getActionUrl('index', 'step'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                } elseif ($step == 2) {
                                    if (in_array('wp-toolkit', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_install_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (Modules_WelcomeBusiness_Helper::isInstalled('kolab')) {
                                        $content .= pm_Locale::lmsg('message_step_kolab', [
                                            'link_kolab' => '/modules/kolab/'
                                        ]);
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_kolab_not', [
                                            'link_install' => pm_Context::getActionUrl('index', 'install') . '?extension=kolab'
                                        ]);
                                    }

                                    if (in_array(Modules_WelcomeBusiness_Helper::getAdvisorData(), $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_ssl_inactive', [
                                            'class'             => 'todo',
                                            'link_advisor_name' => Modules_WelcomeBusiness_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (in_array('pagespeed-insights', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_inactive', [
                                            'class' => 'todo'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_next', [
                                        'link_next'       => pm_Context::getActionUrl('index', 'step'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                } elseif ($step == 3) {
                                    if (in_array('wp-toolkit', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_install_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (in_array('kolab', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_kolab_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (Modules_WelcomeBusiness_Helper::isInstalled(Modules_WelcomeBusiness_Helper::getAdvisorData())) {
                                        $content .= pm_Locale::lmsg('message_step_ssl', [
                                            'link_security'     => '/modules/' . Modules_WelcomeBusiness_Helper::getAdvisorData() . '/',
                                            'link_advisor_name' => Modules_WelcomeBusiness_Helper::getAdvisorData('name')
                                        ]);
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_ssl_not', [
                                            'link_install'      => pm_Context::getActionUrl('index', 'install') . '?extension=' . Modules_WelcomeBusiness_Helper::getAdvisorData(),
                                            'link_advisor_name' => Modules_WelcomeBusiness_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (in_array('pagespeed-insights', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_inactive', [
                                            'class' => 'todo'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_next', [
                                        'link_next'       => pm_Context::getActionUrl('index', 'step'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                } elseif ($step == 4) {
                                    if (in_array('wp-toolkit', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_install_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (in_array('kolab', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_kolab_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (in_array(Modules_WelcomeBusiness_Helper::getAdvisorData(), $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_ssl_inactive', [
                                            'class'             => 'complete',
                                            'link_advisor_name' => Modules_WelcomeBusiness_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (Modules_WelcomeBusiness_Helper::isInstalled('pagespeed-insights')) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed', [
                                            'link_pagespeed' => '/modules/pagespeed-insights/'
                                        ]);
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_not', [
                                            'link_install' => pm_Context::getActionUrl('index', 'install') . '?extension=pagespeed-insights'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_finish', [
                                        'link_finish' => pm_Context::getActionUrl('index', 'step'),
                                    ]);
                                } elseif ($step == 5) {
                                    $content .= pm_Locale::lmsg('message_step_restart', [
                                        'link_restart'    => pm_Context::getActionUrl('index', 'restart'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                }
                            }

                            $message = pm_Locale::lmsg('message_container', ['content' => $content]);

                            if (pm_View_Status::hasMessage($message) == false) {
                                pm_View_Status::addInfo($message, true);
                            }

                            pm_Settings::set('executed', time());
                        }
                    }
                }
            }
        }
    }
}
