{*
 * 2017-2018 PrestaPatron
 *
 * PrestaPatron Announcement Block
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    PrestaPatron
 *  @copyright 2017-2018 PrestaPatron
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
*}

<style type="text/css">
	#announcement-banner, .banner {
	  background: {$banner_bg_color} !important;  
	  color:{$text_color};
	}

	.announcement-link  {
		background: {$button_bg_color};
		color: {$button_text_color};
	}
	
	.announcement-link:hover {
		background: {$button_text_color};
		color: {$button_bg_color};
	}

	.countdown-container .countdown-heading {	 
	  color: {$text_color};
	}

	.countdown-container .countdown-value {	
	  background: {$button_bg_color};	
	  color: {$button_text_color};	
	}

	.coupon-text, .coupon-code {
		color: {$text_color};
	}


</style>
{$today = $smarty.now|date_format:'%Y-%m-%d'}
{if $enable_announcement && $announcement_start_date <= $today && $announcement_end_date >= $today }
{if $announcement_type == 1 }
<div id="announcement-banner">		
	<div class="row">
		<div class="container">
			<div class="simple col-sm-12 col-md-12">
				<div class="announcement">	
					{if isset($announcement_text) && $announcement_text}
						<span class="announcement-text">
							{$announcement_text}
						</span>
					{/if}
					{if isset($announcement_link_text) && $announcement_link_text}
						<a class="announcement-link" href='{$announcement_link}'>
							{$announcement_link_text}
						</a>
					{/if}
				</div>	
			</div>	
		</div>	
	</div>
	<div class="alert-close">×</div>
</div>
{/if}
{if $announcement_type == 2}
<div id="announcement-banner">		
	<div class="row">
		<div class="container">
			<div class="countdown-simple col-sm-12 col-md-12">
				<div class="col-xs-12 col-sm-12 col-md-8">
					<div class="announcement">	
						{if isset($announcement_text) && $announcement_text}
							<span class="announcement-text">
								{$announcement_text}
							</span>
						{/if}
						{if isset($announcement_link_text) && $announcement_link_text}
							<a class="announcement-link" href='{$announcement_link}'>
								{$announcement_link_text}
							      	
							</a>
						{/if}
					</div>	
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4">
					<div class='countdown' data-date="{$announcement_end_date}"></div>			
				</div>
			</div>
		</div>
	</div>
	<div class="alert-close">×</div>
</div>
{/if}

{if $announcement_type == 3}
<div id="announcement-banner">		
	<div class="row">
		<div class="container">
			<div class="countdown-coupon col-sm-12">
				<div class="col-sm-3">
					<p class="coupon-text">
						Code: 
						<span class="coupon-code">
							{if isset($announcement_code) && $announcement_code}
					           {$announcement_code}
					       {else}
					           Coupon Code
					       {/if}					
						</span>
					</p>
				</div>
				<div class="col-sm-6">
					<div class="announcement">	
						{if isset($announcement_text) && $announcement_text}
							<span class="announcement-text">
								{$announcement_text}
							</span>
						{/if}
						{if isset($announcement_link_text) && $announcement_link_text}
							<a target="blank" class="announcement-link" href='{$announcement_link}'>
								{$announcement_link_text}
							      	
							</a>
						{/if}
					</div>	
				</div>
				<div class="col-sm-3">					
					<div class='countdown' data-date="{$announcement_end_date}"></div>			
				</div>
			</div>
		</div>
	</div>
	<div class="alert-close">×</div>
</div>
{/if}
{/if}

{literal}
<script type="text/javascript">
jQuery(document).ready(function() {		
	var myDate = new Date();
	myDate.setDate(myDate.getDate() + 2);
	jQuery("#countdown").countdown(myDate, function (event) {
	  jQuery(this).html(
	      event.strftime(
	          '<div class="timer-wrapper"><div class="time">%D</div><span class="text">days</span></div><div class="timer-wrapper"><div class="time">%H</div><span class="text">hrs</span></div><div class="timer-wrapper"><div class="time">%M</div><span class="text">mins</span></div><div class="timer-wrapper"><div class="time">%S</div><span class="text">sec</span></div>'
	      )
	  );   
	}); 	

}); 
</script>
{/literal}