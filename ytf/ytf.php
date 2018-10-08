<?php
/**
 * Ytf - YouTube Fetcher
 *
 * LICENSE: FREE MIT.
 *
 * @author     Main Author <@fadidev>
 * @copyright  @2018 PimClick
 * @version    CVS: 1.0.0
 * @package    Main file of ytf module
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ytf extends Module
{
    protected $config_form = false;

    /**
    * youTube, youTubeLoadMore function properties
    */
    public $videoList = null;
    public $apiFinalLink = null;

    /**
    * youTubeLoadMore, jCRequest function properties
    */
    public $finalResponse = null;
    public $responseJSON = null;
    public $tokenValue = null;
    public $items = array();

    /**
    * module functions youTube, youTubeLoadMore, jCRequest constants
    */
    const API_KEY = 'YourYouTubeApiKey';
    const CHANNEL_ID = 'YouTubeChannelID';
    const DEFAULT_PER_PAGE_LIMIT = '28';
    const LIMIT_PER_RESULT = '4';

    public function __construct()
    {
        $this->name = 'ytf';
        $this->tab = 'social_networks';
        $this->version = '1.0.0';
        $this->author = '@fadidev';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Ytf - YouTube Fetcher');
        $this->description = $this->l('This module will fetch automatic latest videos from youtube channel.');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('YTF_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header');
    }

    public function uninstall()
    {
        Configuration::deleteByName('YTF_LIVE_MODE');

        return parent::uninstall();
    }

   public function hookHeader()
   {
       if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
        $this->context->controller->registerJavascript('modules-ytf-front',
                'modules/' . $this->name . '/views/js/front.js', array(
                'position' => 'bottom',
                'priority' => 150
              ));
        $this->context->controller->registerJavascript('modules-ytf-swal-front',
        'https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.js', array(
                'server' => 'remote',
                'position' => 'bottom',
                'priority' => 150
              ));
        $this->context->controller->registerJavascript('modules-ytf-fancyjs-front',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js', array(
                'server' => 'remote',
                'position' => 'bottom',
                'priority' => 150
              ));
        $this->context->controller->registerStylesheet('modules-ytf-swalcss-front',
        'https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css', array(
                'server' => 'remote',
                'media' => 'all',
                'priority' => 150
              ));
        $this->context->controller->registerStylesheet('modules-ytf-front',
        'https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.css', array(
                'server' => 'remote',
                'media' => 'all',
                'priority' => 150
              ));
        $this->context->controller->registerStylesheet('modules-fancycss-front',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css', array(
                'server' => 'remote',
                'media' => 'all',
                'priority' => 150
              ));
       } else {
         $this->context->controller->addJS($this->_path.'/views/js/front.js');
         $this->context->controller->addJS('https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.js');
         $this->context->controller->addJS('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js');
         $this->context->controller->addCSS('https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css');
         $this->context->controller->addCSS('https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.css');
         $this->context->controller->addCSS('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css');
       }
   }
}
