<?php
 
// Include Composer autoloader if not already done.
include 'uploads/pdf/pdfparser-0.14.0/vendor/autoload.php';
 
// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
//$filename1 = $_FILES["file"]["name"];

$pdf    = $parser->parseFile($_FILES["pdf_file"]["tmp_name"]);
 
// Retrieve all pages from the pdf file.
$pages  = $pdf->getPages();
 
// Loop over each page to extract text.

$i=1;
foreach ($pages as $page) {
    //echo $i++; 
	//echo "<hr>";
  //  echo $page->getText();
}
//exit;
?>
<?php

$title = $_POST['title'];
$status = $_POST['status'];
function fixForUri($string)
{
    $slug = trim($string); // trim the string
    $slug = preg_replace('/[^a-zA-Z0-9 -]/', '', $slug); // only take alphanumerical characters, but keep the spaces and dashes too...
    $slug = str_replace(' ', '-', $slug); // replace spaces by dashes
    $slug = strtolower($slug);  // make it lowercase
    return $slug; 
}
$filename1 = $_FILES["file"]["name"];
$file_basename1 = substr($filename1, 0, strripos($filename1, '.')); // get file extention
$file_ext = substr($filename1, strripos($filename1, '.')); // get file name
//$filesize = $_FILES["image_file"]["size"];
// $allowed_file_types = array('.jpg','.jpeg','.png');
$newImage_file = trim(str_replace('.', '', $filename1)) . rand() . $file_ext;;
if (move_uploaded_file($_FILES["file"]["tmp_name"], 'uploads/issues/'. $newImage_file)) {

}
$conn = mysqli_connect("localhost", "laurelre_site", "oLdrGUXucriTBHwnAfSO4yp7QoplisCE", "laurelre_dev");
$row = 1;

$fileName = $_FILES["csv_file"]["tmp_name"];
if (($handle = fopen($fileName, "r")) !== FALSE) {
    $the_big_array = array();
    $data_value = array();
    $data_value1 = array();
    $slug_title = fixForUri($title);
    $sqlIssue = "INSERT INTO `issues` (`id`, `slug`, `title`, `status`, `inventory`, `created`, `modified`) VALUES (NULL, '".$slug_title."', '".$title."', '".$status."', '0', UNIX_TIMESTAMP(), NULL)";
    $result1 = mysqli_query($conn, $sqlIssue);
    $sql = "SELECT MAX(id) FROM issues";
    $result = $conn->query($sql);
    $issue_ids = $result->fetch_row();
    $issue_id = $issue_ids[0];

    $issueable_id = "INSERT INTO `issue_files` (`id`, `issueable_id`, `issueable_type`, `name`, `storage_name`, `access_key`, `preview_key`, `mime`, `meta`, `status`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "','".addslashes('Project\\Models\\Issue')."' , '".$newImage_file."', '".$newImage_file."', NULL, NULL, 'image/jpeg', NULL, NULL, UNIX_TIMESTAMP(), NULL)"; 
    $result1 = mysqli_query($conn, $issueable_id);

	$issue_content = "INSERT INTO `issue_content` (`id`, `issue_id`, `name`, `title`, `content`, `content_text`) VALUES (NULL, '" . $issue_id . "', 'short_description', '".$title."', '<p>Spring  2019</p>', 'Spring 2019'),(NULL, '" . $issue_id . "', 'before TOC', '".$title."', NULL, NULL),(NULL, '" . $issue_id . "', 'After TOC', '".$title."', NULL, NULL), (NULL, '" . $issue_id . "', 'Aside TOC', '".$title."', NULL, NULL)";
    $result1 = mysqli_query($conn, $issue_content);

    $issue_toc_order = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
        if ($data[0] && $data[1] && $data[2] && $data[3]) {
            // echo "testing";
            $sqlInsert1 = "INSERT INTO `issue_toc` (`id`, `issue_id`, `order`, `is_header`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $issue_toc_order . "', '1', '" . $data[0] . "', '" . $data[0] . "', UNIX_TIMESTAMP(), NULL) ";

            $result1 = mysqli_query($conn, $sqlInsert1);

            $issue_toc_order++;
            $sqlInsert2 = "INSERT INTO `issue_toc` (`id`, `issue_id`, `order`, `is_header`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "',  '" . $issue_toc_order . "', '0','" . $data[1] . "', '" . $data[1] . "', UNIX_TIMESTAMP(), NULL)";
            $result2 = mysqli_query($conn, $sqlInsert2);
            $lastinsert_id = mysqli_insert_id($conn);

            $sqlInsert3 = "INSERT INTO `issue_toc_titles` (`id`, `issue_toc_id`, `order`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $lastinsert_id . "', '0', '" . $data[2] . "', '" . $data[2] . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert3);
            $lastTOCinsert_id = mysqli_insert_id($conn);

            $slug = fixForUri($data[2]);

			$page_number1=1;
			foreach ($pages as $page) {
					if($page_number1 == trim($data[3])) {
			$sqlInsert4 = "INSERT INTO `issue_toc_contents` (`id`, `issue_id`, `issue_toc_title_id`, `slug`, `status`, `highlight`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $lastTOCinsert_id . "', '" . $slug . "', '1', '1', '" . addslashes($page->getText()) . "', '" . addslashes($page->getText()) . "', UNIX_TIMESTAMP(), NULL)";
            $result4 = mysqli_query($conn, $sqlInsert4);
			   }
				$page_number1++; 
     		}

        } else if (!$data[0] && !$data[1] && $data[2] && !$data[3]) {

            $sql = "SELECT MAX(id) FROM issue_toc";
            $result = $conn->query($sql);
            $row = $result->fetch_row();

            $sqlInsert3 = "INSERT INTO `issue_toc_titles` (`id`, `issue_toc_id`, `order`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $row[0] . "', '0', '" . addslashes($data[2]) . "', '" . addslashes($data[2]) . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert3);


        } else if (!$data[0] && $data[1] && $data[2] && $data[3]) {
            $sqlInsert2 = "INSERT INTO `issue_toc` (`id`, `issue_id`, `order`, `is_header`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $issue_toc_order . "', '0','" . $data[1] . "', '" . $data[1] . "',UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert2);
            $lastinsert_id = mysqli_insert_id($conn);

            $sqlInsert3 = "INSERT INTO `issue_toc_titles` (`id`, `issue_toc_id`, `order`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $lastinsert_id . "', '0', '" . addslashes($data[2]) . "', '" . addslashes($data[2]) . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert3);
            $lastTOCinsert_id = mysqli_insert_id($conn);
            $slug = fixForUri($data[2]);
            
			
			$page_number2=1;
			foreach ($pages as $page) {
			if($page_number2 == trim($data[3])) {
			$sqlInsert4 = "INSERT INTO `issue_toc_contents` (`id`, `issue_id`, `issue_toc_title_id`, `slug`, `status`, `highlight`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $lastTOCinsert_id . "', '" . $slug . "', '1', '1', '" . addslashes($page->getText()) . "', '" . addslashes($page->getText()) . "', UNIX_TIMESTAMP(), NULL)";
            $result4 = mysqli_query($conn, $sqlInsert4);
			   }
				$page_number2++; 
     		}

        } else if ($data[0] && $data[1] && $data[2] && !$data[3]) {

            $sqlInsert1 = "INSERT INTO `issue_toc` (`id`, `issue_id`, `order`, `is_header`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $issue_toc_order . "', '1', '" . $data[0] . "', '" . $data[0] . "', UNIX_TIMESTAMP(), NULL) ";

            $result1 = mysqli_query($conn, $sqlInsert1);
            $issue_toc_order++;
            $sqlInsert2 = "INSERT INTO `issue_toc` (`id`, `issue_id`, `order`, `is_header`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $issue_toc_order . "', '0','" . $data[1] . "', '" . $data[1] . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert2);
            $lastinsert_id = mysqli_insert_id($conn);

            $sqlInsert3 = "INSERT INTO `issue_toc_titles` (`id`, `issue_toc_id`, `order`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $lastinsert_id . "', '0', '" . addslashes($data[2]) . "', '" . addslashes($data[2]) . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert3);


        } else if (!$data[0] && !$data[1] && $data[2] && $data[3]) {
            $sql = "SELECT MAX(id) FROM issue_toc";
            $result = $conn->query($sql);
            $row = $result->fetch_row();

            $sqlInsert3 = "INSERT INTO `issue_toc_titles` (`id`, `issue_toc_id`, `order`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $row[0] . "', '0', '" . addslashes($data[2]) . "', '" . addslashes($data[2]) . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert3);
            $lastTOCinsert_id = mysqli_insert_id($conn);
            $slug = fixForUri($data[2]);
            
			$page_number3=1;
			foreach ($pages as $page) {
			if($page_number3 == trim($data[3])) {
				$sqlInsert4 = "INSERT INTO `issue_toc_contents` (`id`, `issue_id`, `issue_toc_title_id`, `slug`, `status`, `highlight`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $lastTOCinsert_id . "', '" . $slug . "', '1', '1', '" . addslashes($page->getText()) . "', '" . addslashes($page->getText()) . "', UNIX_TIMESTAMP(), NULL)";
            $result4 = mysqli_query($conn, $sqlInsert4);
			   }
				$page_number3++; 
     		}
		
        } else if (!$data[0] && $data[1] && $data[2] && !$data[3]) {

            $sqlInsert2 = "INSERT INTO `issue_toc` (`id`, `issue_id`, `order`, `is_header`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $issue_id . "', '" . $issue_toc_order . "', '0','" . $data[1] . "', '" . $data[1] . "',UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert2);
            $lastinsert_id = mysqli_insert_id($conn);

            $sqlInsert3 = "INSERT INTO `issue_toc_titles` (`id`, `issue_toc_id`, `order`, `content`, `content_text`, `created`, `modified`) VALUES (NULL, '" . $lastinsert_id . "', '0', '" . addslashes($data[2]) . "', '" . addslashes($data[2]) . "', UNIX_TIMESTAMP(), NULL)";

            $result2 = mysqli_query($conn, $sqlInsert3);
        }
        $issue_toc_order++;
    }
    fclose($handle);
    header("Location: http://qua.laurelreview.org/storyadmin/issues");
}
?>
