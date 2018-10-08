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
{extends file='page.tpl'}

{block name="page_content"}

  <div class="container">
    <div class="row" id="titleRow">
      <h4 style="padding:20px;font-weight:500;">&nbsp;&nbsp;&nbsp;Total videos:&nbsp;<strong>
        {if $search eq "true"}
        {$YouTube.videoList->items|@count}
        {else}
        {$YouTube.videoList->pageInfo->totalResults}
        {/if}
      </strong></h4>
    </div>
    <div class="row">
      {if $YouTube.videoList->items|@count eq "0"}
      <p style="padding-left:30px;color:#fd4f00;">No result are found.</p>
      <br/>
      {/if}
    </div>
    <div class="row" id="searchRow" style="padding:0px 0px 10px 28px;">
    <form action="" method="get" enctype="multipart/form-data" name="formsearch" id="formsearch">
      <input type="text" class="" name="q" id="q" value="" placeholder="Search any keyword.." style="width:25%;height:15%;text-align:-webkit-center;font-size:14px;" required/>
      <button type="button" id="submits" class="btn btn-success" style="cursor:pointer;background-color:#fd4f00;border-color:#cc4203;border-radius:3px;" title="Search" onclick="document.formsearch.submit();">Search</button>
    </form>
    </div>
    <div class="" id="mainRow">
      {foreach $YouTube.videoList->items as $item} {if isset($item->id->videoId) && isset($item->snippet->thumbnails->medium->url)}
      <div class="col-sm-3 col-md-3 col-xs-12 col-lg-3 animated fadeIn" id="youTube">
        <a href="https://www.youtube.com/embed/{$item->id->videoId}" alt="{$item->snippet->title}" title="{$item->snippet->title}">
        <img src="{$item->snippet->thumbnails->medium->url}" alt="{$item->snippet->title}" title="{$item->snippet->title}" style="width:100%;cursor:pointer;"/>
        </a>
        <p style="color: #4f4e4e;padding-top: 20px; min-height: 55px;"><strong>{$item->snippet->title}</strong></p>
      </div>
      {/if} {/foreach}
    </div>
    <div class="ajax-load text-center" style="display:none">
      <center><p><img src="../img/loader.gif">&nbsp;Loading more videos..</p></center>
    </div>
    {if $search != "true"}
    <button type="button" id="loadmore" class="btn btn-success" style="width:50%;margin-left:25%;margin-bottom:2%;cursor:pointer; background-color: #fd4f00;
    border-color: #cc4203; border-radius: 3px;" title="Load more videos..">Load more videos..</button>
    {/if}
  </div>
  <script>
  jQuery('#loadmore').click(function() {
    var finalToken = (localStorage.getItem('token') == null ? "{$YouTube.videoList->nextPageToken}" : localStorage.getItem('token'));
    jCRequest(finalToken);
  });
  </script>

{/block}
