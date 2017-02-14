<?php

get_header(); ?>
<div class="webinar-video-container network-page">
    <div class="webinar-video" >

        <div class="page-banner">
            <div class="banner-text">Monthly Webinars</div>
        </div>
    </div>


    <?php
    # This section generates the blurb under the title
    the_post();
    the_content();
    ?>


    <?php
    function dateToCal($timestamp) {
        return date('Ymd\THis\Z', $timestamp);
    }

    $webinar_time = get_post_meta($post->ID, "webinar_time", true);
    $webinar_phone = get_post_meta($post->ID, "webinar_phone", true);
    $webinar_pin = get_post_meta($post->ID, "webinar_pin", true);
    $webinar_date = get_post_meta($post->ID, "webinar_date", true);
    $webinar_link = get_post_meta($post->ID, "webinar_link", true);
    $todaysDate = time() - (time() % 86400);
    $vimeo_video_id = get_post_meta($post->ID, 'vimeo_video_id', true);

		if (is_user_logged_in() ) {
			if ( strtotime($webinar_date) <= $todaysDate and !empty($vimeo_video_id)) {
                ?> <iframe src="https://player.vimeo.com/video/<?php echo $vimeo_video_id;?>" width="950" height="534" frameborder="0" webkitallowfullscreen mozallowfullscreen allow fullscreen></iframe><?php
            }
            elseif(strtotime($webinar_date) >= $todaysDate){ ?>
                <?/*This script, ics.deps.min.js version 0.1.3, was developed by Travis Krause and Kyle Hornberg, https://github.com/nwcell/ics.js,
        and was released under the MIT License. I altered in on Nov 10, 2015 to fit the needs of this site.*/?>
                <script>
                    var saveAs=saveAs || typeof navigator!=="undefined" && navigator.msSaveOrOpenBlob && navigator.msSaveOrOpenBlob.bind(navigator) || function(e){"use strict";if(typeof navigator!=="undefined"&&/MSIE [1-9]\./.test(navigator.userAgent)){return}var t=e.document,n=function(){return e.URL||e.webkitURL||e},r=e.URL||e.webkitURL||e,i=t.createElementNS("http://www.w3.org/1999/xhtml","a"),s=!e.externalHost&&"download"in i,o=function(n){var r=t.createEvent("MouseEvents");r.initMouseEvent("click",true,false,e,0,0,0,0,0,false,false,false,false,0,null);n.dispatchEvent(r)},u=e.webkitRequestFileSystem,a=e.requestFileSystem||u||e.mozRequestFileSystem,f=function(t){(e.setImmediate||e.setTimeout)(function(){throw t},0)},l="application/octet-stream",c=0,h=[],p=function(){var e=h.length;while(e--){var t=h[e];if(typeof t==="string"){r.revokeObjectURL(t)}else{t.remove()}}h.length=0},d=function(e,t,n){t=[].concat(t);var r=t.length;while(r--){var i=e["on"+t[r]];if(typeof i==="function"){try{i.call(e,n||e)}catch(s){f(s)}}}},v=function(r,o){var f=this,p=r.type,v=false,m,g,y=function(){var e=n().createObjectURL(r);h.push(e);return e},b=function(){d(f,"writestart progress write writeend".split(" "))},w=function(){if(v||!m){m=y(r)}if(g){g.location.href=m}else{window.open(m,"_blank")}f.readyState=f.DONE;b()},E=function(e){return function(){if(f.readyState!==f.DONE){return e.apply(this,arguments)}}},S={create:true,exclusive:false},x;f.readyState=f.INIT;if(!o){o="download"}if(s){m=y(r);t=e.document;i=t.createElementNS("http://www.w3.org/1999/xhtml","a");i.href=m;i.download=o;var T=t.createEvent("MouseEvents");T.initMouseEvent("click",true,false,e,0,0,0,0,0,false,false,false,false,0,null);i.dispatchEvent(T);f.readyState=f.DONE;b();return}if(e.chrome&&p&&p!==l){x=r.slice||r.webkitSlice;r=x.call(r,0,r.size,l);v=true}if(u&&o!=="download"){o+=".download"}if(p===l||u){g=e}if(!a){w();return}c+=r.size;a(e.TEMPORARY,c,E(function(e){e.root.getDirectory("saved",S,E(function(e){var t=function(){e.getFile(o,S,E(function(e){e.createWriter(E(function(t){t.onwriteend=function(t){g.location.href=e.toURL();h.push(e);f.readyState=f.DONE;d(f,"writeend",t)};t.onerror=function(){var e=t.error;if(e.code!==e.ABORT_ERR){w()}};"writestart progress write abort".split(" ").forEach(function(e){t["on"+e]=f["on"+e]});t.write(r);f.abort=function(){t.abort();f.readyState=f.DONE};f.readyState=f.WRITING}),w)}),w)};e.getFile(o,{create:false},E(function(e){e.remove();t()}),E(function(e){if(e.code===e.NOT_FOUND_ERR){t()}else{w()}}))}),w)}),w)},m=v.prototype,g=function(e,t){return new v(e,t)};m.abort=function(){var e=this;e.readyState=e.DONE;d(e,"abort")};m.readyState=m.INIT=0;m.WRITING=1;m.DONE=2;m.error=m.onwritestart=m.onprogress=m.onwrite=m.onabort=m.onerror=m.onwriteend=null;e.addEventListener("unload",p,false);g.unload=function(){p();e.removeEventListener("unload",p,false)};return g}(typeof self!=="undefined"&&self||typeof window!=="undefined"&&window||this.content);if(typeof module!=="undefined")module.exports=saveAs;if(!(typeof Blob==="function"||typeof Blob==="object")||typeof URL==="undefined")if((typeof Blob==="function"||typeof Blob==="object")&&typeof webkitURL!=="undefined")self.URL=webkitURL;else var Blob=function(e){"use strict";var t=e.BlobBuilder||e.WebKitBlobBuilder||e.MozBlobBuilder||e.MSBlobBuilder||function(e){var t=function(e){return Object.prototype.toString.call(e).match(/^\[object\s(.*)\]$/)[1]},n=function(){this.data=[]},r=function(t,n,r){this.data=t;this.size=t.length;this.type=n;this.encoding=r},i=n.prototype,s=r.prototype,o=e.FileReaderSync,u=function(e){this.code=this[this.name=e]},a=("NOT_FOUND_ERR SECURITY_ERR ABORT_ERR NOT_READABLE_ERR ENCODING_ERR "+"NO_MODIFICATION_ALLOWED_ERR INVALID_STATE_ERR SYNTAX_ERR").split(" "),f=a.length,l=e.URL||e.webkitURL||e,c=l.createObjectURL,h=l.revokeObjectURL,p=l,d=e.btoa,v=e.atob,m=e.ArrayBuffer,g=e.Uint8Array;r.fake=s.fake=true;while(f--){u.prototype[a[f]]=f+1}if(!l.createObjectURL){p=e.URL={}}p.createObjectURL=function(e){var t=e.type,n;if(t===null){t="application/octet-stream"}if(e instanceof r){n="data:"+t;if(e.encoding==="base64"){return n+";base64,"+e.data}else if(e.encoding==="URI"){return n+","+decodeURIComponent(e.data)}if(d){return n+";base64,"+d(e.data)}else{return n+","+encodeURIComponent(e.data)}}else if(c){return c.call(l,e)}};p.revokeObjectURL=function(e){if(e.substring(0,5)!=="data:"&&h){h.call(l,e)}};i.append=function(e){var n=this.data;if(g&&(e instanceof m||e instanceof g)){var i="",s=new g(e),a=0,f=s.length;for(;a<f;a++){i+=String.fromCharCode(s[a])}n.push(i)}else if(t(e)==="Blob"||t(e)==="File"){if(o){var l=new o;n.push(l.readAsBinaryString(e))}else{throw new u("NOT_READABLE_ERR")}}else if(e instanceof r){if(e.encoding==="base64"&&v){n.push(v(e.data))}else if(e.encoding==="URI"){n.push(decodeURIComponent(e.data))}else if(e.encoding==="raw"){n.push(e.data)}}else{if(typeof e!=="string"){e+=""}n.push(unescape(encodeURIComponent(e)))}};i.getBlob=function(e){if(!arguments.length){e=null}return new r(this.data.join(""),e,"raw")};i.toString=function(){return"[object BlobBuilder]"};s.slice=function(e,t,n){var i=arguments.length;if(i<3){n=null}return new r(this.data.slice(e,i>1?t:this.data.length),n,this.encoding)};s.toString=function(){return"[object Blob]"};return n}(e);return function(n,r){var i=r?r.type||"":"";var s=new t;if(n){for(var o=0,u=n.length;o<u;o++){s.append(n[o])}}return s.getBlob(i)}}(typeof self!=="undefined"&&self||typeof window!=="undefined"&&window||this.content||this);var ics=function(){"use strict";if(navigator.userAgent.indexOf("MSIE")>-1&&navigator.userAgent.indexOf("MSIE 10")==-1){console.log("Unsupported Browser");return}var e=navigator.appVersion.indexOf("Win")!==-1?"\r\n":"\n";var t=[];var n=["BEGIN:VCALENDAR","VERSION:2.0"].join(e);var r=e+"END:VCALENDAR";return{events:function(){return t},calendar:function(){return n+e+t.join(e)+r},addEvent:function(n,r,i,s,o,u){if(typeof n==="undefined"||typeof u==="undefined"||typeof r==="undefined"||typeof i==="undefined"||typeof s==="undefined"||typeof o==="undefined"){return false}var N=["BEGIN:VEVENT","CLASS:PUBLIC","UID"+u,"DESCRIPTION:"+r,"DTSTART:"+s,"DTEND:"+o,"LOCATION:"+i,"SUMMARY;LANGUAGE=en-us:"+n,"TRANSP:TRANSPARENT","END:VEVENT"].join(e);t=[];t.push(N);return N},download:function(i,s){if(t.length<1){return false}s=typeof s!=="undefined"?s:".ics";i=typeof i!=="undefined"?i:"calendar";var o=n+e+t.join(e)+r;var u;if(navigator.userAgent.indexOf("MSIE 10")===-1){u=new Blob([o])}else{var a=new BlobBuilder;a.append(o);u=a.getBlob("text/x-vCalendar;charset="+document.characterSet)}saveAs(u,i+s);}}}
                </script>

					<div class="join-box" id="single-webinar">
						<div class="login-credentials">Login Credentials</div>
						<div class="login-credentials">Dial in Number:<span> <?php echo $webinar_phone ?></span></div>
						<div class="login-credentials">Pin:<span> <?php echo $webinar_pin ?></span></div>
                        <a href="<?php echo  $webinar_link?>" target="_blank" style="color: white; font-size: 14px !important"><button>Join the Webinar</button></a>
                        <?php
                        $calendar_description = get_post_meta($post->ID, "calendar_description", true);
                        $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                        $name = 'SRS Webinar: ' . get_the_title();
                        $time2 = strtotime("+6 hours".$webinar_date." ".$webinar_time);
                        $content =addslashes(get_the_author() . "" . '\n' . $calendar_description . "" .'\n' . "Join the Webinar: " . $webinar_link);
                        echo '<button class="blue" style="font-size: 14px !important" onclick=\'downloadICS("'.  $name .'", "'. datetoCal($time2) .'", "'. $content .'")\'>Add to Cal</button>';?>

					</div>

                <script>
                    var cal = ics();
                    function downloadICS(name, time, content){
                        cal.addEvent(name, content, 'Webinar', time, time, time);
                        cal.download(name);
                    }
                </script>
                <?php
            }
        }
        else{
			$permalink = get_permalink($post->ID);
            echo '<div class="join-box" id="single-webinar">';
                if ( strtotime($webinar_date) <= $todaysDate){
                    if(empty($vimeo_video_id)){
                        echo 'Webinar Video Coming Soon';
                    }
                    else{
                        echo 'Sign in to Watch the Webinar Video';
                    }
                }
                else{
                    echo 'Sign in to Join the Webinar';
                };
            echo '<br><br>
                <a href="/supportraisingsolutions.org/login/?redirect_to= %2Fwebinars%2F'. $post->post_name . '">Sign In</a> | <a href="mailto:info@supportraisingsolutions.org?subject=SRS Network Membership&body=I am interested in the SRS Monthly Webinars. Please contact me with more information.">Join</a>
            </div>';
        }

    $id = "logged-out";
    if (is_user_logged_in() and strtotime($webinar_date) <= $todaysDate) {
        $id = "logged-in";
    }?>


    <div class="title-wrapper" id="<?php echo $id?>">
        <?php if((is_user_logged_in()) and (strtotime($webinar_date) <= $todaysDate)) {?>
            <div class="section-header">
                <span id="author"><?php the_author(); echo ":</span> <span id=\"title\">"; the_title(); ?></span>
            </div>
            <?php
        };?>

        <div class="date-time-wrapper" id="<?php echo $id?>">
            <div class="srs-webinar-note" id="<?php echo $id?>"> SRS Webinar</div>
            <div class="date">
                <?php
                 if (!empty($webinar_date)) {
                     $date = new DateTime($webinar_date);
                      $webinar_date = $date->format('  M j, Y');
                      echo $webinar_date;
                  };
                 ?>
            </div>
            <div class="event-time" id="<?php echo $id?>">
                  <?php
                   if (!empty($webinar_time)) {
                        $time = new DateTime($webinar_time);
                        $webinar_time = $time->format('g:i a ');
                        echo $webinar_time;
                    }; echo 'CT';
                  ?>
            </div>
        </div>

        <?php
        $webinar_date = get_post_meta($post->ID, "webinar_date", true);
        if(strtotime($webinar_date) <= $todaysDate and is_user_logged_in()) {?>
            <div id="button-wrapper">
                <a href="<?php bloginfo('url'); ?>/webinars/">
                    <button> BACK TO ARCHIVE</button>
                </a>
            </div>
        <?php }
        else{
            ?>
            <div class="section-header" id="logged-out">
                <span id="author"><?php the_author(); echo ": </span>"; echo the_title(); ?></span>
            </div>
        <?php } ?>

        <div class="video-description">
            <?php $video_description = get_post_meta($post->ID, "video_description", true);
            echo $video_description;
            ?>

            <?php
            $webinar_presentation = get_post_meta($post->ID, 'webinar_presentation', true);
            if ($webinar_presentation) { ?>
                <div id="button-wrapper_presentation">
                    <a href="<?php echo $webinar_presentation[guid]; ?>">
                        <button>DOWNLOAD PRESENTATION</button>
                    </a>
                </div>
            <?php } ?>

        </div>
    </div>

    <div class = "leader-bio-wrapper">
        <div class="leader-bio">
                <?php echo get_wp_user_avatar($userID); ?>
            <br>
<!--            get_author_posts_url-->
            <div class="author-name-wrapper">
                <?php the_author();

                    echo ", ";
                 ?>
            </div>

            <div class="job-title-wrapper">
            <?php
                $jobtitle = get_the_author_meta("job_title");
                if (!empty($jobtitle)) {
                echo $jobtitle.", ";
                } ?>
            </div>
            <div class = "organization-title-wrapper">
                    <?php $organization = get_the_author_meta("organization");
                    if (!empty($organization)) {
                        echo $organization;
                    } ?>
            </div>
            <div class = "bio-info-wrapper">
                <?php $bioinfo = get_the_author_meta("description");
                if (!empty($bioinfo)) {
                    echo $bioinfo."<br>";
                } ?>
            </div>
            <div class="bio-info-button-wrapper">
                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'));?>">
<!--                        "--><?php //bloginfo('url'); ?><!--/blog/">-->
                        <button> SRS Blog Articles</button>
                    </a>
            </div>
        </div>
    </div>
<!--    --><?php
//        $output .= get_dynamic_column( 'cont-box-3', 'one_third home-cont-box', 'catapult-video-page-column-1' );
//        $output .= get_dynamic_column( 'cont-box-4', 'one_third home-cont-box second_column', 'catapult-video-page-column-2' );
//        $output .= get_dynamic_column( 'cont-box-4', 'one_third home-cont-box last_column', 'catapult-video-page-column-3' );
//        echo $output;
//    ?>
</div>
<?php get_footer(); ?>