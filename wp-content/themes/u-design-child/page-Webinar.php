<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Webinar
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>

    <?/*This script, ics.deps.min.js version 0.1.3, was developed by Travis Krause and Kyle Hornberg, https://github.com/nwcell/ics.js,
        and was released under the MIT License. I altered in on Nov 10, 2015 to fit the needs of this site.*/?>
    <script>
        var saveAs=saveAs||typeof navigator!=="undefined"&&navigator.msSaveOrOpenBlob&&navigator.msSaveOrOpenBlob.bind(navigator)||function(e){"use strict";if(typeof navigator!=="undefined"&&/MSIE [1-9]\./.test(navigator.userAgent)){return}var t=e.document,n=function(){return e.URL||e.webkitURL||e},r=e.URL||e.webkitURL||e,i=t.createElementNS("http://www.w3.org/1999/xhtml","a"),s=!e.externalHost&&"download"in i,o=function(n){var r=t.createEvent("MouseEvents");r.initMouseEvent("click",true,false,e,0,0,0,0,0,false,false,false,false,0,null);n.dispatchEvent(r)},u=e.webkitRequestFileSystem,a=e.requestFileSystem||u||e.mozRequestFileSystem,f=function(t){(e.setImmediate||e.setTimeout)(function(){throw t},0)},l="application/octet-stream",c=0,h=[],p=function(){var e=h.length;while(e--){var t=h[e];if(typeof t==="string"){r.revokeObjectURL(t)}else{t.remove()}}h.length=0},d=function(e,t,n){t=[].concat(t);var r=t.length;while(r--){var i=e["on"+t[r]];if(typeof i==="function"){try{i.call(e,n||e)}catch(s){f(s)}}}},v=function(r,o){var f=this,p=r.type,v=false,m,g,y=function(){var e=n().createObjectURL(r);h.push(e);return e},b=function(){d(f,"writestart progress write writeend".split(" "))},w=function(){if(v||!m){m=y(r)}if(g){g.location.href=m}else{window.open(m,"_blank")}f.readyState=f.DONE;b()},E=function(e){return function(){if(f.readyState!==f.DONE){return e.apply(this,arguments)}}},S={create:true,exclusive:false},x;f.readyState=f.INIT;if(!o){o="download"}if(s){m=y(r);t=e.document;i=t.createElementNS("http://www.w3.org/1999/xhtml","a");i.href=m;i.download=o;var T=t.createEvent("MouseEvents");T.initMouseEvent("click",true,false,e,0,0,0,0,0,false,false,false,false,0,null);i.dispatchEvent(T);f.readyState=f.DONE;b();return}if(e.chrome&&p&&p!==l){x=r.slice||r.webkitSlice;r=x.call(r,0,r.size,l);v=true}if(u&&o!=="download"){o+=".download"}if(p===l||u){g=e}if(!a){w();return}c+=r.size;a(e.TEMPORARY,c,E(function(e){e.root.getDirectory("saved",S,E(function(e){var t=function(){e.getFile(o,S,E(function(e){e.createWriter(E(function(t){t.onwriteend=function(t){g.location.href=e.toURL();h.push(e);f.readyState=f.DONE;d(f,"writeend",t)};t.onerror=function(){var e=t.error;if(e.code!==e.ABORT_ERR){w()}};"writestart progress write abort".split(" ").forEach(function(e){t["on"+e]=f["on"+e]});t.write(r);f.abort=function(){t.abort();f.readyState=f.DONE};f.readyState=f.WRITING}),w)}),w)};e.getFile(o,{create:false},E(function(e){e.remove();t()}),E(function(e){if(e.code===e.NOT_FOUND_ERR){t()}else{w()}}))}),w)}),w)},m=v.prototype,g=function(e,t){return new v(e,t)};m.abort=function(){var e=this;e.readyState=e.DONE;d(e,"abort")};m.readyState=m.INIT=0;m.WRITING=1;m.DONE=2;m.error=m.onwritestart=m.onprogress=m.onwrite=m.onabort=m.onerror=m.onwriteend=null;e.addEventListener("unload",p,false);g.unload=function(){p();e.removeEventListener("unload",p,false)};return g}(typeof self!=="undefined"&&self||typeof window!=="undefined"&&window||this.content);if(typeof module!=="undefined")module.exports=saveAs;if(!(typeof Blob==="function"||typeof Blob==="object")||typeof URL==="undefined")if((typeof Blob==="function"||typeof Blob==="object")&&typeof webkitURL!=="undefined")self.URL=webkitURL;else var Blob=function(e){"use strict";var t=e.BlobBuilder||e.WebKitBlobBuilder||e.MozBlobBuilder||e.MSBlobBuilder||function(e){var t=function(e){return Object.prototype.toString.call(e).match(/^\[object\s(.*)\]$/)[1]},n=function(){this.data=[]},r=function(t,n,r){this.data=t;this.size=t.length;this.type=n;this.encoding=r},i=n.prototype,s=r.prototype,o=e.FileReaderSync,u=function(e){this.code=this[this.name=e]},a=("NOT_FOUND_ERR SECURITY_ERR ABORT_ERR NOT_READABLE_ERR ENCODING_ERR "+"NO_MODIFICATION_ALLOWED_ERR INVALID_STATE_ERR SYNTAX_ERR").split(" "),f=a.length,l=e.URL||e.webkitURL||e,c=l.createObjectURL,h=l.revokeObjectURL,p=l,d=e.btoa,v=e.atob,m=e.ArrayBuffer,g=e.Uint8Array;r.fake=s.fake=true;while(f--){u.prototype[a[f]]=f+1}if(!l.createObjectURL){p=e.URL={}}p.createObjectURL=function(e){var t=e.type,n;if(t===null){t="application/octet-stream"}if(e instanceof r){n="data:"+t;if(e.encoding==="base64"){return n+";base64,"+e.data}else if(e.encoding==="URI"){return n+","+decodeURIComponent(e.data)}if(d){return n+";base64,"+d(e.data)}else{return n+","+encodeURIComponent(e.data)}}else if(c){return c.call(l,e)}};p.revokeObjectURL=function(e){if(e.substring(0,5)!=="data:"&&h){h.call(l,e)}};i.append=function(e){var n=this.data;if(g&&(e instanceof m||e instanceof g)){var i="",s=new g(e),a=0,f=s.length;for(;a<f;a++){i+=String.fromCharCode(s[a])}n.push(i)}else if(t(e)==="Blob"||t(e)==="File"){if(o){var l=new o;n.push(l.readAsBinaryString(e))}else{throw new u("NOT_READABLE_ERR")}}else if(e instanceof r){if(e.encoding==="base64"&&v){n.push(v(e.data))}else if(e.encoding==="URI"){n.push(decodeURIComponent(e.data))}else if(e.encoding==="raw"){n.push(e.data)}}else{if(typeof e!=="string"){e+=""}n.push(unescape(encodeURIComponent(e)))}};i.getBlob=function(e){if(!arguments.length){e=null}return new r(this.data.join(""),e,"raw")};i.toString=function(){return"[object BlobBuilder]"};s.slice=function(e,t,n){var i=arguments.length;if(i<3){n=null}return new r(this.data.slice(e,i>1?t:this.data.length),n,this.encoding)};s.toString=function(){return"[object Blob]"};return n}(e);return function(n,r){var i=r?r.type||"":"";var s=new t;if(n){for(var o=0,u=n.length;o<u;o++){s.append(n[o])}}return s.getBlob(i)}}(typeof self!=="undefined"&&self||typeof window!=="undefined"&&window||this.content||this);var ics=function(){"use strict";if(navigator.userAgent.indexOf("MSIE")>-1&&navigator.userAgent.indexOf("MSIE 10")==-1){console.log("Unsupported Browser");return}var e=navigator.appVersion.indexOf("Win")!==-1?"\r\n":"\n";var t=[];var n=["BEGIN:VCALENDAR","VERSION:2.0"].join(e);var r=e+"END:VCALENDAR";return{events:function(){return t},calendar:function(){return n+e+t.join(e)+r},addEvent:function(n,r,i,s,o){if(typeof n==="undefined"||typeof r==="undefined"||typeof i==="undefined"||typeof s==="undefined"||typeof o==="undefined"){return false}var N=["BEGIN:VEVENT","CLASS:PUBLIC","DESCRIPTION:"+r,"DTSTART:"+s,"DTEND:"+o,"LOCATION:"+i,"SUMMARY;LANGUAGE=en-us:"+n,"TRANSP:TRANSPARENT","END:VEVENT"].join(e);t.push(N);return N},download:function(i,s){if(t.length<1){return false}s=typeof s!=="undefined"?s:".ics";i=typeof i!=="undefined"?i:"calendar";var o=n+e+t.join(e)+r;var u;if(navigator.userAgent.indexOf("MSIE 10")===-1){u=new Blob([o])}else{var a=new BlobBuilder;a.append(o);u=a.getBlob("text/x-vCalendar;charset="+document.characterSet)}saveAs(u,i+s);}}}
    </script>

<div id="content-container" class="full-width">
	<div id="main-content" class="full-width">
		<div id="network" class="network-page webinar">
			<div class="page-banner">
        <div class="banner-text">Monthly Webinars</div>
      </div>
      <div class="description-container">
        <div class="intro-text"><span>Develop yourself and grow your staff</span> through one-hour, live webinars led by veteran trainers 
        and coaches every month. Receive training beyond Bootcamp to help coach your staff through 
        obstacles in their personal support raising</div>
        <div class="bullet-columns"><div class="detail-heading">Monthly Webinar Benefits</div>
          <ul>
            <div class="bullet-left">
              <li>Receive training beyond Bootcamp to help coach your staff</li>
              <li>Get questions answered in real time</li>
            </div>
            <div class="bullet-right">
              <li>Receive updates and news about new SRS Network benefits</li>
              <li>Access to archived webinars</li>
            </div>
          </ul>
        </div>
        <div class="clear"></div>
      </div>
      <div class="join-box">
        SRS Network Members Webinar Access
        <br>
        <a href="/supportraisingsolutions.org/login/?redirect_to=%2Fsupportraisingsolutions.org%2Fwebinar">Sign In</a> | <a href="mailto:info@supportraisingsolutions.org">Join</a>
      </div>
      <div class="clear"></div>
      <div class="event-container">

 <?php
         function dateToCal($timestamp) {
             return date('Ymd\THis\Z', $timestamp);
         }

        $global_posts_query = new WP_Query(
            array(
                'post_type' => 'webinar',
                'meta_query' => array(
                    array(
                        'key' => 'webinar_date',
                        'compare' => '>=',
                        'type' => 'DATE',
                    )
                ),
                'meta_key' => 'webinar_date',
                'orderby' => 'meta_value',
                'order' => 'DESC',
                'posts_per_page' => 5
            )
        );


        if($global_posts_query->have_posts()) :
          while($global_posts_query->have_posts()) : $global_posts_query->the_post(); ?>
            <div class="event">
              <div class="event-border"></div>
              <div class="leader-bio">
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'));?>"><?php echo get_wp_user_avatar($userID); ?>
                  <span class="leader-name"><?php the_author(); ?></span></a><br>
                <?php
                $jobtitle = get_the_author_meta("job_title");
                if (!empty($jobtitle)) {
                  echo $jobtitle.",<br>";
                } ?>
                <i>
                  <?php $organization = get_the_author_meta("organization");
                  if (!empty($organization)) {
                    echo $organization;
                  } ?>
                </i>
              </div>
              <div class="event-header">
                <?php the_title(); ?>
              </div>
              <div class="event-time">
                <div class="date">
                  <?php $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                  if (!empty($webinar_date)) {
                    $date = new DateTime($webinar_date);
                    $webinar_date = $date->format('M j');
                    echo $webinar_date;
                  }
                  ?>
                  <div class="year">
                    <?php $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                    if (!empty($webinar_date)) {
                      $date = new DateTime($webinar_date);
                      $webinar_date = $date->format('Y');
                      echo $webinar_date;
                    }
                    ?>
                  </div>
                </div>
                <div class="line"></div>
            <?
            $webinar_date = get_post_meta($post->ID, "webinar_date", true);
            $todaysDate = time() - (time() % 86400);
            if ( strtotime($webinar_date) >= $todaysDate) { ?><div class="time-of-day">
                  <?php $webinar_time = get_post_meta($post->ID, "webinar_time", true);
                  if (!empty($webinar_time)) {
                    $time = new DateTime($webinar_time);
                    $webinar_time = $time->format('g a ');
                    echo $webinar_time;
                  }
                  ?>CT
                </div><? }?>

                  <?php

                      ?>
                  <?php
                  $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                  $todaysDate = time() - (time() % 86400);
                  if ( is_user_logged_in() ) {
                      if ( strtotime($webinar_date) <= $todaysDate) {echo '<button>Watch Now</button>';}
                      else{
                          $webinar_time = get_post_meta($post->ID, "webinar_time", true);
                          if (!empty($webinar_time)) {
                              $time = new DateTime($webinar_time);
                              $webinar_time = $time->format(' G:i');
                          }


                          $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                          if (!empty($webinar_date)) {
                              $date = new DateTime($webinar_date);
                              $webinar_date = $date->format('M d, Y');
                          }

                          $temp = strtotime("+6 hours".$webinar_date." ".$webinar_time);
                          echo '<button class="blue"><a href="javascript:cal.download(\'Webinar\')" style="color: white;">Add to Cal</a></button>';}
                  }
                  ?>
                  <script>
                      var cal = ics();
                      cal.addEvent('Webinar', 'Content', 'Location', '<? echo datetoCal($temp); ?>', '<? echo datetoCal($temp);  ?>');
                  </script>
              </div>
            </div>
            <div class="clear"></div>
          <? endwhile;
        endif;
 ?>

      </div>
        </div>
    </div><!-- end main-content -->
</div><!-- end content-container -->
  <div class="clear"></div>


<?php

get_footer();