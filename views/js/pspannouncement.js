/**
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
*/
$(document).ready(function(c) {	
	$('.alert-close').on('click', function(c){
		$('.banner').fadeOut('slow', function(c){
	  		$('.banner').remove();
		});
	});	
});
