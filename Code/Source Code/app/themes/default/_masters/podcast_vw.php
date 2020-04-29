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

$result=mysqli_query($conn1,"SELECT `id`, `slug`, `description`, `content`, `content_text`, `profile_img_path`, `audio_img_path`, `status`,    
FROM_UNIXTIME(created,'%Y, %D %M') as date ,  
FROM_UNIXTIME(created,'%h:%i:%s') as time ,  
`created`, `modified`, `snippet` FROM `dev_podcast` WHERE  status='1' order by id desc  ");

	/*while ($row = mysqli_fetch_array($result)) {
       echo $row['description'];
   }*/

?>

</div> </div>	</div>	</div>

<div class="site-section bg-light">
    <div class="container">

        <div class="row mb-5" data-aos="fade-up">
            <div class="col-md-12 text-center">
                <h2 class="font-weight-bold text-black">Recent Podcasts</h2>
            </div>
        </div>


        <?php $i =1; while ($row = mysqli_fetch_array($result)) {
        $i++;

        $slug = $row['slug'];
        $description = $row['description'];
        $content = $row['content'];
        $content_text = $row['content_text'];
        $profile_img_path = $url . "/uploads/podcast/" . $row['profile_img_path'];
        $audio_img_path = $url . "/uploads/podcast/" . $row['audio_img_path'];
        $created = $row['created'];
            $date = $row['date'];
            $time = $row['time'];
        $snippet = $row['snippet'];

        //  print_r($row['slug']);
        //` `slug`, `description`, `content`, `content_text`, `profile_img_path`, `audio_img_path`, `status`, `created`, `modified`, `snippet`


        ?>

        <div class="d-block d-md-flex podcast-entry bg-white mb-5" data-aos="fade-up">
            <div class="image" style="background-image: url('<?php echo $profile_img_path;?>');"></div>
            <div class="text">

                <h3 class="font-weight-light"><a href="single-post.html"><?php echo $slug; ?></a></h3>
                <div class="text-white mb-3"><span class="text-black-opacity-05"><small><?php echo $description; ?>  <span class="sep">/</span> <?php echo $date; ?> <span class="sep">/</span> <?php echo $time; ?></small></span></div>
                <p class="mb-4"> <?php echo $content; ?></p>

                <div class="player">
                    <audio id="player2" preload="none" controls style="max-width: 100%">
                        <source src="<?php echo $audio_img_path; ?>" type="audio/mp3">
                    </audio>
                </div>

            </div>
        </div>

        <?php   } 
        ?>



    </div>
</div>
<div> <div>	<div>	<div>