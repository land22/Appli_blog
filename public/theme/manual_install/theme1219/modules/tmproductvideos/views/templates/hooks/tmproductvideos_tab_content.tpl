{if isset($videos) && $videos}
	<div id="product-video-tab-content" class="product-video-tab-content tab-pane">
        {foreach from=$videos item=video name=myvideo}
            {if $video.type == 'youtube'}
                <div class="videowrapper">
                    <iframe type="text/html" 
                        src="{$video.link|escape:'html'}?enablejsapi=1&version=3&html5=1&wmode=transparent"
                        frameborder="0"
                        wmode="Opaque"></iframe>
                </div>
           {elseif $video.type == 'vimeo'}
                <div class='embed-container'>
                    <iframe 
                        src="{$video.link|escape:'html'}"
                        frameborder="0"
                        webkitAllowFullScreen
                        mozallowfullscreen
                        allowFullScreen>
                    </iframe>
                </div>
            {/if}
            {if $video.name}
                <h4 class="video-name">{$video.name|escape:'html'}</h4>
            {/if}
            {if $video.description}
                <p class="video-description">{$video.description}</p>
            {/if}
        {/foreach}
    </div>
{/if}