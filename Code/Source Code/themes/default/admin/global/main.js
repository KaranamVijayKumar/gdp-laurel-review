/*! Story_admin_theme - v2.0.6 | (c) Arpad Olasz | Proprietary. */
var setContentToolbarTopPadding;!function(){"use strict";var a=$("body");if($.ajaxSetup({data:{_token:$(":hidden[name=_token]").val()},success:function(a){Object.prototype.hasOwnProperty.call(a,"timeout")&&Object.prototype.hasOwnProperty.call(a,"timeout_url")&&a.timeout===!0&&a.timeout_url&&(window.location=a.timeout_url)}}),$.fn.wsNotify&&$(".notifications > ol").wsNotify(),$.fn.wsEditable&&$(".flag--editable").wsEditable(),$.fn.wsSelectAll&&$(".select-all").wsSelectAll(),$.fn.tabs){var b=$(".tabs").tabs({tabContent:".tabs-content",activeTabClass:"tabs__item--selected"});$(":hidden[name=openTab]").length||b.tabs("open","first")}$(".nav--main__handle").click(function(a){a.preventDefault(),$(".nav--main").toggleClass("active").find(">ul:first").scrollTop(0),$(this).toggleClass("active")});var c=$(".nav--main > ul"),d=function(){var a=$(this),b=$(window).height()-($(window).width()<1024?$(".global-header").outerHeight():parseInt($(".content").css("margin-top"),10));a.css("height",b)};d.apply(c);var e=$(".content--toolbar"),f=$(".toolbar");setContentToolbarTopPadding=function(){var a=f.outerHeight()-($(window).width()<1024?36:24);e.css("padding-top")!==a+"px"&&e.css("padding-top",a)},setContentToolbarTopPadding(),$(window).on("resize",function(){d.apply(c),setContentToolbarTopPadding()});var g,h=function(a){var b=$(this);13===a.which&&a.preventDefault(),clearTimeout(g),g=setTimeout(function(){jQuery.get(b.data("url"),{q:b.val()},function(a){$(b.data("content")).first().replaceWith(a.items)},"json")},300)},i=function(a){a.stopPropagation();var b=$(this).data("confirm"),c="Are you sure?";b=void 0===b?"":"\n\n"+b,"undefined"!=typeof window.ws&&"undefined"!=typeof window.ws.confirm&&(c=window.ws.confirm);var d=confirm(c+b);return d||a.stopImmediatePropagation(),d};a.on("keyup blur paste cut",".flag--editable [contenteditable=true]",function(){setTimeout(function(){setContentToolbarTopPadding()})}).on("keypress keyup paste cut",".actions__search",h).on("click.confirm",".confirm",i),a.on("change",".actions__select",function(){var a=$(this).data("base"),b=$(".actions__search").val().trim(),c=[],d=jQuery.param({q:b});$(".actions__select").each(function(){c.push($(this).val())}),a=a+"/"+c.join("/")+(b?"?"+d:""),window.location=a}),jQuery().chosen&&$("select.chosen-select").chosen(),jQuery().fieldloader&&$("select.js-fieldloader").fieldloader(),jQuery().multiple&&$("div.js-multiple").multiple({add:function(a){$(":input:visible:enabled:first",a).focus()}}),a.on("click",".js-section-selector",function(a){a.preventDefault();var b=this.hash.substr(1);$("#"+b).toggleClass("hidden"),$(window).scrollTop($("#"+b).offset().top-$(".toolbar").outerHeight()-$(".global-header").outerHeight()-24)});var j=function(){var a=$(".js-show-details__container",this),b=$(".js-show-details__selector",this),c="visuallyhidden",d=$("> *",a);d.each(function(){$("input,textarea,select",this).attr("disabled","disabled")}),d.addClass(c),b.each(function(){var d=b.prop("tagName");if("INPUT"===d)b.filter(":checked").each(function(){var b=$(this).val(),d=$("> .js-show-details__"+b,a);d.removeClass(c),$("input,textarea,select",d).removeAttr("disabled")});else if("SELECT"===d){var e=b.val(),f=$("> .js-show-details__"+e,a);f.removeClass(c),$("input,textarea,select",f).removeAttr("disabled")}})};$(".js-show-details").each(j),a.on("change",".js-show-details",j),a.on("click",".js-help",function(a){a.preventDefault();var b=$(this).attr("href"),c=444,d=window.screenLeft+$(window).width()-c,e=$(window).height()/2,f=window.screenTop;window.open(b,"help","directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,resizable=yes,width="+c+",height="+e+",top="+f+",left="+d)}),$("form:not(.filter) :input:visible:enabled:first",".container").focus()}();