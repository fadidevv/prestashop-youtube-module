<?php
/**
 * Ytf - YouTube Fetcher
 *
 * LICENSE: This module is PimClick Copyright. Please contact us if you need
 * some clarification or custom editing.
 *
 * @author     Main Author <@fadidev>
 * @copyright  @2018 PimClick
 * @version    CVS: 1.0.0
 * @package    Controller of ytf module
 */

class YtfDefaultModuleFrontController extends ModuleFrontController
{
  public function initContent()
  {
      parent::initContent();

      $this->context->smarty->assign('YouTube',array(
      'videoList' => $this->youTube()
      ));
      $this->setTemplate(version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? 'module:' . $this->module->name .
      '/views/templates/front/youtube.tpl' : '16/youtube.tpl');
      $this->jCRequest();
  }

  public function youTube()
  {
      try {
        if(Tools::getValue('q')) {
          $this->module->apiFinalLink = 'https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.Ytf::CHANNEL_ID.'&maxResults='.Ytf::DEFAULT_PER_PAGE_LIMIT.'&type=video'.'&q='.str_replace(' ', '+', Tools::getValue('q')).'&key='.Ytf::API_KEY;
                $arrContextOptions=array(
            "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
            ),
          );
          $this->context->smarty->assign('search', 'true');
          return $this->module->videoList = json_decode(Tools::file_get_contents($this->module->apiFinalLink, false, stream_context_create($arrContextOptions)));
        } else {
          $this->module->apiFinalLink = 'https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.Ytf::CHANNEL_ID.'&maxResults='.Ytf::DEFAULT_PER_PAGE_LIMIT.'&key='.Ytf::API_KEY;
                $arrContextOptions=array(
            "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
            ),
          );
          return $this->module->videoList = json_decode(Tools::file_get_contents($this->module->apiFinalLink, false, stream_context_create($arrContextOptions)));
        }
      } catch (Exception $e) {
          echo $e->getMessage();
      }
  }

  public function youTubeLoadMore($pageToken)
  {
      try {
        if(Tools::getValue('q')) {
          $this->module->apiFinalLink = 'https://www.googleapis.com/youtube/v3/search?pageToken=' . $pageToken . '&order=date&part=snippet&channelId=' . Ytf::CHANNEL_ID . '&maxResults=' . Ytf::LIMIT_PER_RESULT . '&type=video' . '&q=' . str_replace(' ', '+', Tools::getValue('q')) . '&key=' . Ytf::API_KEY;
            $arrContextOptions  = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false
                )
          );
          return $this->module->videoList = json_decode(Tools::file_get_contents($this->module->apiFinalLink, false, stream_context_create($arrContextOptions)));
        } else {
          $this->module->apiFinalLink = 'https://www.googleapis.com/youtube/v3/search?pageToken=' . $pageToken . '&order=date&part=snippet&channelId=' . Ytf::CHANNEL_ID . '&maxResults=' . Ytf::LIMIT_PER_RESULT . '&key=' . Ytf::API_KEY;
            $arrContextOptions  = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false
                )
          );
          return $this->module->videoList = json_decode(Tools::file_get_contents($this->module->apiFinalLink, false, stream_context_create($arrContextOptions)));
        }
      }
      catch (Exception $e) {
          echo $e->getMessage();
      }
  }

  public function jCRequest()
  {
    try {
        $this->module->tokenValue = Tools::getValue('t');
        if (Tools::getValue('m') == 'more' and Tools::getValue('t') == $this->module->tokenValue and Tools::getValue('l') == Ytf::LIMIT_PER_RESULT) {
        $this->module->responseJSON = $this->youTubeLoadMore(Tools::getValue('t'));
          foreach($this->module->responseJSON->items as $item) {
            if(isset($item->id->videoId) and isset($item->snippet->thumbnails->medium->url)) {
            $this->items[] = array(
              'videoId' => $item->id->videoId,
              'videoTitle' => $item->snippet->title,
              'videoImage' => $item->snippet->thumbnails->medium->url
            );
          }
          }
        if(count($this->module->responseJSON->items) == 0) {
            print_r(Tools::jsonEncode($this->items[] = array(
              'error' => 'No data found'
            )));
          } else {
            print_r(Tools::jsonEncode(array(
                'nextPageToken' => $this->module->responseJSON->nextPageToken,
                'JCYouTube' => $this->items
            )));
          }
      } else {
          $this->module->finalResponse = Tools::jsonEncode(array(
              'status' => 'hasNoAccess'
          ));
      }
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
}
