
<?php 

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Group.php';
require_once 'classes/Category.php';
require_once 'classes/Tag.php';
require_once 'classes/Group_Tag.php';
session_start();


if($_SESSION['logged_in'] != 1){
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
}else{


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if ( isset($_GET['group_title']) && !empty($_GET['group_title']) && isset($_GET['hourly_rate']) && !empty($_GET['hourly_rate']) && isset($_GET['numberOfStudents']) && !empty($_GET['numberOfStudents']) && isset($_GET['group_description']) && !empty($_GET['group_description']) && isset($_GET['category']) && !empty($_GET['category']) && isset($_GET['tags']) && !empty($_GET['tags']) && isset($_GET['group_learning_outcomes']) && !empty($_GET['group_learning_outcomes']) && isset($_GET['scheduled_date']) && !empty($_GET['scheduled_date']) && isset($_GET['time']) && !empty($_GET['time'])) {
            $tags = explode(",",$_GET['tags']);
            $safeTags = [];

            $newGroup = new Group;
            $safeGroup_title = $newGroup->sanitize($_GET['group_title']);
            $safeGroup_description = $newGroup->sanitize($_GET['group_description']);
            $safeGroup_learning_outcomes = $newGroup->sanitize($_GET['group_learning_outcomes']);
            $safeCategory = $newGroup->sanitize($_GET['category']);

            $status = "pending";
            if($newGroup->addGroup($_SESSION['user_id'], $safeGroup_title, $safeGroup_description, $safeGroup_learning_outcomes, $status, $_GET['scheduled_date'], $_GET['time'], $_GET['hourly_rate'], $_GET['numberOfStudents'], $_GET['category'])){

                for($keyt = 0; $keyt< count($tags); $keyt++){
                    $checkTag = new Tag;
                    $safeTag = $checkTag->sanitize(strtolower($tags[$keyt]));
                    if($checkTag->checkTagIfExist($safeTag)){
                        $checkTag->getTagInfoFromDatabaseByText($safeTag);
                        $safeTags[] = $checkTag->getTag_id();
                    }else{
                        $newTag = new Tag;
                        if($newTag->addTag($safeTag)){
                            $safeTags[] = $newTag->getTag_id();
                        }
                    }
                }

                for ($tagKey = 0; $tagKey < count($safeTags); $tagKey++){
                    $add_group_tag = new Group_Tag;
                    if ($add_group_tag->addTagForGroup($newGroup->getGroup_id(),$safeTags[$tagKey])){
                        //tag is added
                    }
                }
                header("Location: tutoringclass.php?group_id=". $newGroup->getGroup_id());
                
            }else{
         
               header("Location: find.php");
            }

        }else{
        	header("Location: find.php");
        }
    }else{
    	header("Location: find.php");
    }
}

?>