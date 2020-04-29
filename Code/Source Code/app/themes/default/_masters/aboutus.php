<?php // print_r($_SERVER);
if($_SERVER['SERVER_NAME']=="localhost"){
    $url= "http://localhost/laurelview/";
    $conn1 = mysqli_connect("localhost", "root", "", "laurelre_dev");
} elseif ($_SERVER['SERVER_NAME']=="laurelreview.org"){
    $url= "http://laurelreview.org/";
    $conn1 = mysqli_connect("localhost", "laurelre_site", "oLdrGUXucriTBHwnAfSO4yp7QoplisCE", "laurelre_dev");
} else {
    $url= "http://qua.laurelreview.org/";
   $conn1 = mysqli_connect("localhost", "laurelre_site", "oLdrGUXucriTBHwnAfSO4yp7QoplisCE", "laurelre_dev");
}
?>

<?php
//include "config.php";

$result=mysqli_query($conn1,"SELECT `id`, `slug`, `description`, `content`, `content_text`, `profile_img_path`, `status`, `created`, `modified`, `insta_link`, `fb_link`, `twitter_link`, `wordpress_link` FROM `dev_aboutus` WHERE status='1' ");

	/*while ($row = mysqli_fetch_array($result)) {
       echo $row['description'];
   }*/

?>

</div></div></div></div>
               <div class="clearfix-slt"></div>
         <div id="content-slt">
            <div class="width-container-slt">
               <div id="post-18" class="post-18 page type-page status-publish hentry">
                  <div class="page-content-slt">
                     <div data-elementor-type="post" data-elementor-id="18" class="elementor elementor-18" data-elementor-settings="[]">
                        <div class="elementor-inner">
                           <div class="elementor-section-wrap">
						   
						   
                              <section class="elementor-element elementor-element-pnqysno elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="pnqysno" data-element_type="section">
                                 <div class="elementor-container elementor-column-gap-no">
                                    <div class="elementor-row">
                                       <div class="elementor-element elementor-element-ponojdw elementor-column elementor-col-100 elementor-top-column" data-id="ponojdw" data-element_type="column">
                                          <div class="elementor-column-wrap  elementor-element-populated">
                                             <div class="elementor-widget-wrap">

                                                 <?php $i =1; while ($row = mysqli_fetch_array($result)) {
                                                     $i++;

                                                     $slug = $row['slug'];
                                                     $description = $row['description'];
                                                     $content = $row['content'];
                                                     $content_text = $row['content_text'];
                                                     $profile_img_path = $url . "/uploads/aboutus/" . $row['profile_img_path'];
                                                     $fb_link = $row['fb_link'];
                                                     $insta_link = $row['insta_link'];
                                                     $twitter_link = $row['twitter_link'];
                                                     $wordpress_link = $row['wordpress_link'];

                                                     //  print_r($row['slug']);
                                                     //`id`, `slug`, `description`, `content`, `content_text`, `profile_img_path`, `status`, `created`, `modified`, `insta_link`, `fb_link`, `twitter_link`, `wordpress_link`

                                                     if ($i % 2 == 0) {
                                                         ?>
                                                         <section
                                                                 class="elementor-element elementor-element-qbm7xeh elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-inner-section"
                                                                 data-id="qbm7xeh" data-element_type="section">
                                                             <div class="elementor-container elementor-column-gap-wide">
                                                                 <div class="elementor-row">


                                                                     <div class="elementor-element elementor-element-edbc5ix elementor-column elementor-col-50 elementor-inner-column"
                                                                          data-id="edbc5ix" data-element_type="column">
                                                                         <div class="elementor-column-wrap  elementor-element-populated">
                                                                             <div class="elementor-widget-wrap">
                                                                                 <div class="elementor-element elementor-element-fwq1m4r elementor-widget elementor-widget-image"
                                                                                      data-id="fwq1m4r"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="image.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <div class="elementor-image">
                                                                                             <img width="600"
                                                                                                  height="776"
                                                                                                  src="<?php echo $profile_img_path; ?>"
                                                                                                  class="attachment-full size-full"
                                                                                                  alt=""/>
                                                                                             <noscript><img width="600"
                                                                                                            height="776"
                                                                                                            src="<?php echo $profile_img_path; ?>"
                                                                                                            class="attachment-full size-full"
                                                                                                            alt=""
                                                                                                            srcset="<?php echo $profile_img_path; ?> 600w, john.png 232w"
                                                                                                            sizes="(max-width: 600px) 100vw, 600px"/>
                                                                                             </noscript>
                                                                                         </div>
                                                                                     </div>
                                                                                 </div>
                                                                                 <div class="elementor-element elementor-element-69r8cjj elementor-shape-circle elementor-widget elementor-widget-social-icons"
                                                                                      data-id="69r8cjj"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="social-icons.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <div class="elementor-social-icons-wrapper">
                                                                                             <a href="<?php echo $fb_link; ?>"
                                                                                                target="_blank"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-facebook-f elementor-repeater-item-3sh22jv">
                                                                                                 <span class="elementor-screen-only">Facebook-f</span>
                                                                                                 <i class="fab fa-facebook-f"></i>
                                                                                             </a>
                                                                                             <a href="<?php echo $twitter_link; ?> "
                                                                                                target="_blank"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-twitter elementor-repeater-item-gcixnfd">
                                                                                                 <span class="elementor-screen-only">Twitter</span>
                                                                                                 <i class="fab fa-twitter"></i>
                                                                                             </a>
                                                                                             <a href="<?php echo $insta_link; ?>"
                                                                                                target="_blank"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-13tqb9d">
                                                                                                 <span class="elementor-screen-only">Instagram</span>
                                                                                                 <i class="fab fa-instagram"></i>
                                                                                             </a>
                                                                                             <a href="<?php echo $wordpress_link; ?>"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-soundcloud elementor-repeater-item-3ed6b14"
                                                                                                target="_blank">
                                                                                                 <span class="elementor-screen-only">Soundcloud</span>
                                                                                                 <i class="fab fa-soundcloud"></i>
                                                                                             </a>
                                                                                         </div>
                                                                                     </div>
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>

                                                                     <div class="elementor-element elementor-element-279m8eo elementor-column elementor-col-50 elementor-inner-column"
                                                                          data-id="279m8eo" data-element_type="column">
                                                                         <div class="elementor-column-wrap  elementor-element-populated">
                                                                             <div class="elementor-widget-wrap">
                                                                                 <div class="elementor-element elementor-element-hymgd1l elementor-widget elementor-widget-heading"
                                                                                      data-id="hymgd1l"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="heading.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <h3 class="elementor-heading-title elementor-size-default"><?php echo $slug ?></h3>
                                                                                     </div>
                                                                                 </div>
                                                                                 <div class="elementor-element elementor-element-mb7b1cs elementor-widget elementor-widget-heading"
                                                                                      data-id="mb7b1cs"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="heading.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <span class="elementor-heading-title elementor-size-default"><?php echo $description;?></span>
                                                                                     </div>
                                                                                 </div>
                                                                                 <div class="elementor-element elementor-element-hnnco85 elementor-widget elementor-widget-text-editor"
                                                                                      data-id="hnnco85"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="text-editor.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <div class="elementor-text-editor elementor-clearfix">
                                                                                             <p style="text-align: left;"><?php echo $content_text ?></p>
                                                                                         </div>
                                                                                     </div>
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>


                                                                 </div>
                                                             </div>
                                                         </section>
                                                         <div class="elementor-element elementor-element-ia6b04m elementor-widget elementor-widget-divider"
                                                              data-id="ia6b04m" data-element_type="widget"
                                                              data-widget_type="divider.default">
                                                             <div class="elementor-widget-container">
                                                                 <div class="elementor-divider"><span
                                                                             class="elementor-divider-separator"></span>
                                                                 </div>
                                                             </div>
                                                         </div>

                                                      <?php   } else {
                                                         ?>


                                                         <section
                                                                 class="elementor-element elementor-element-4113df5 elementor-reverse-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-inner-section"
                                                                 data-id="4113df5" data-element_type="section">
                                                             <div class="elementor-container elementor-column-gap-wide">
                                                                 <div class="elementor-row">
                                                                     <div class="elementor-element elementor-element-b84fcad elementor-column elementor-col-50 elementor-inner-column"
                                                                          data-id="b84fcad" data-element_type="column">
                                                                         <div class="elementor-column-wrap  elementor-element-populated">
                                                                             <div class="elementor-widget-wrap">
                                                                                 <div class="elementor-element elementor-element-442e1e4 elementor-widget elementor-widget-heading"
                                                                                      data-id="442e1e4"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="heading.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <h3 class="elementor-heading-title elementor-size-default">
                                                                                             <?php echo $slug; ?></h3>
                                                                                     </div>
                                                                                 </div>
                                                                                 <div class="elementor-element elementor-element-79df861 elementor-widget elementor-widget-heading"
                                                                                      data-id="79df861"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="heading.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <span class="elementor-heading-title elementor-size-default"><?php echo $description; ?></span>
                                                                                     </div>
                                                                                 </div>
                                                                                 <div class="elementor-element elementor-element-e3948ed elementor-widget elementor-widget-text-editor"
                                                                                      data-id="e3948ed"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="text-editor.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <div class="elementor-text-editor elementor-clearfix">
                                                                                             <p style="text-align: left;"><?php echo $content; ?></p>
                                                                                         </div>
                                                                                     </div>
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                     <div class="elementor-element elementor-element-8f6f464 elementor-column elementor-col-50 elementor-inner-column"
                                                                          data-id="8f6f464" data-element_type="column">
                                                                         <div class="elementor-column-wrap  elementor-element-populated">
                                                                             <div class="elementor-widget-wrap">
                                                                                 <div class="elementor-element elementor-element-7617ec6 elementor-widget elementor-widget-image"
                                                                                      data-id="7617ec6"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="image.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <div class="elementor-image">
                                                                                             <img width="600"
                                                                                                  height="776"
                                                                                                  src="<?php echo $profile_img_path; ?>"
                                                                                                  class="attachment-full size-full"
                                                                                                  alt=""/>
                                                                                             <noscript><img width="600"
                                                                                                            height="776"
                                                                                                            src="<?php echo $profile_img_path; ?>"
                                                                                                            class="attachment-full size-full"
                                                                                                            alt=""
                                                                                                            srcset="<?php echo $profile_img_path; ?> 600w, john.png 232w"
                                                                                                            sizes="(max-width: 600px) 100vw, 600px"/>
                                                                                             </noscript>
                                                                                         </div>
                                                                                     </div>
                                                                                 </div>
                                                                                 <div class="elementor-element elementor-element-dff9c63 elementor-shape-circle elementor-widget elementor-widget-social-icons"
                                                                                      data-id="dff9c63"
                                                                                      data-element_type="widget"
                                                                                      data-widget_type="social-icons.default">
                                                                                     <div class="elementor-widget-container">
                                                                                         <div class="elementor-social-icons-wrapper">
                                                                                             <a href="<?php echo $fb_link; ?>"
                                                                                                target="_blank"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-facebook-f elementor-repeater-item-3sh22jv">
                                                                                                 <span class="elementor-screen-only">Facebook-f</span>
                                                                                                 <i class="fab fa-facebook-f"></i>
                                                                                             </a>
                                                                                             <a href="<?php echo $twitter_link; ?> "
                                                                                                target="_blank"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-twitter elementor-repeater-item-gcixnfd">
                                                                                                 <span class="elementor-screen-only">Twitter</span>
                                                                                                 <i class="fab fa-twitter"></i>
                                                                                             </a>
                                                                                             <a href="<?php echo $insta_link; ?>"
                                                                                                target="_blank"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-repeater-item-13tqb9d">
                                                                                                 <span class="elementor-screen-only">Instagram</span>
                                                                                                 <i class="fab fa-instagram"></i>
                                                                                             </a>
                                                                                             <a href="<?php echo $wordpress_link; ?>"
                                                                                                class="elementor-icon elementor-social-icon elementor-social-icon-soundcloud elementor-repeater-item-3ed6b14"
                                                                                                target="_blank">
                                                                                                 <span class="elementor-screen-only">Soundcloud</span>
                                                                                                 <i class="fab fa-soundcloud"></i>
                                                                                             </a>
                                                                                         </div>
                                                                                     </div>
                                                                                 </div>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </section>

                                                         <?php
                                                     }
                                                 } ?>

                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </section>


                              <section class="elementor-element elementor-element-qpbpnaz elementor-section-stretched elementor-section-full_width elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="qpbpnaz" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
                                 <div class="elementor-container elementor-column-gap-default">
                                    <div class="elementor-row">
                                       <div class="elementor-element elementor-element-ggmrisv elementor-column elementor-col-50 elementor-top-column" data-id="ggmrisv" data-element_type="column">
                                          <div class="elementor-column-wrap  elementor-element-populated">
                                             <div class="elementor-widget-wrap">
                                                <div class="elementor-element elementor-element-bfaa356 elementor-widget elementor-widget-spacer" data-id="bfaa356" data-element_type="widget" data-widget_type="spacer.default">
                                                   <div class="elementor-widget-container">
                                                      <div class="elementor-spacer">
                                                         <div class="elementor-spacer-inner"></div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="elementor-element elementor-element-7323d78 elementor-column elementor-col-50 elementor-top-column" data-id="7323d78" data-element_type="column">
                                          <div class="elementor-column-wrap  elementor-element-populated">
                                             <div class="elementor-widget-wrap">
                                                <div class="elementor-element elementor-element-ef44314 elementor-widget elementor-widget-heading" data-id="ef44314" data-element_type="widget" data-widget_type="heading.default">
                                                   <div class="elementor-widget-container">
                                                      <h2 class="elementor-heading-title elementor-size-default">About Our Podcast</h2>
                                                   </div>
                                                </div>
                                                <div class="elementor-element elementor-element-04961e4 elementor-widget elementor-widget-divider" data-id="04961e4" data-element_type="widget" data-widget_type="divider.default">
                                                   <div class="elementor-widget-container">
                                                      <div class="elementor-divider"> <span class="elementor-divider-separator"></span></div>
                                                   </div>
                                                </div>
                                                <div class="elementor-element elementor-element-20dc47d elementor-widget elementor-widget-text-editor" data-id="20dc47d" data-element_type="widget" data-widget_type="text-editor.default">
                                                   <div class="elementor-widget-container">
                                                      <div class="elementor-text-editor elementor-clearfix">
                                                         <p>The Laurel Review is a biannual print magazine seeking submissions of poetry, fiction, creative nonfiction, script, review, and those that blur the lines between genres. We seek both established and new voices in the writing world, and we are especially interested in writing by those whose identities have been historically under-represented. Submitters living outside of the United States are encouraged to submit. Translations in various genres are also of interest. All accepted submitters will receive two copies of the issue in which their piece(s) appear/s.<br></p>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </section>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="clearfix-slt"></div>
            </div>
         </div><div><div><div><div>