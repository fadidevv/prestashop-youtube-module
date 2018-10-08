{*
  * Ytf - YouTube Fetcher
  *
  * LICENSE: This module is PimClick Copyright. Please contact us if you need
  * some clarification or custom editing.
  *
  * @author     Main Author <@fadidev>
  * @copyright  @2018 PimClick
  * @version    CVS: 1.0.0
  * @package    Main template file of ytf module
*}

  <div class="container">
    <div class="row" id="titleRow">
      <h4>&nbsp;&nbsp;&nbsp;Total videos:&nbsp;<strong>{$YouTube.videoList->pageInfo->totalResults}</strong></h4>
    </div>
    <div class="row" id="mainRow">
      {foreach $YouTube.videoList->items as $item} {if isset($item->id->videoId) && isset($item->snippet->thumbnails->medium->url)}
      <div class="col-sm-3 col-md-3 col-xs-12 col-lg-3 animated fadeIn" id="youTube">
        <a href="https://www.youtube.com/embed/{$item->id->videoId}" alt="{$item->snippet->title}" title="{$item->snippet->title}">
  <img src="{$item->snippet->thumbnails->medium->url}" alt="{$item->snippet->title}" title="{$item->snippet->title}" style="width:100%;cursor:pointer;"/>
  </a>
        <p style="min-height:50px;"><strong>{$item->snippet->title}</strong></p>
      </div>
      {/if} {/foreach}
    </div>
    <div class="ajax-load text-center" style="display:none">
      <center><p><img src="../img/loader.gif">&nbsp;Loading more videos..</p></center>
    </div>
    <button type="button" id="loadmore" class="btn btn-success" style="width:50%;margin-left:25%;margin-bottom:2%;cursor:pointer;" title="Load more videos..">Load more videos..</button>
  </div>
  <script>
    jQuery('#loadmore').click(function() {
      var finalToken = (localStorage.getItem('token') == null ? "{$YouTube.videoList->nextPageToken}" : localStorage.getItem('token'));
      jCRequest(finalToken);
    });
  </script>
