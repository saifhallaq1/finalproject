<?php
include_once ('Header1.php');
?>

<?php

require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Question.php';
require_once 'classes/Answer.php';
require_once 'classes/Category.php';
require_once 'classes/Comment_Answer.php';
require_once 'classes/Tag.php';
require_once 'classes/Questions_Tags.php';


session_start();


if($_SESSION['logged_in'] != 1){
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
}else {


    if (isset($_GET['categoryt']) && !empty($_GET['categoryt'])) {
        # code...

        $category_title = $_GET['categoryt'];
        $checkCategory = new Category;

        $viewQuestions = new Question;

        if ($category_title == "AllCategories") {
            //get all questions
            $questions = $viewQuestions->getAllQuestions();

        } elseif ($checkCategory->getCategoryInfoFromDatabase($category_title)) {
            $questions = $viewQuestions->getQuestionsbyCategory($checkCategory->getcategory_id());
        } else {
            $questions = null;
        }

        if ($questions) {
            $counter = 0;
            $counter1=0;

            foreach ($questions as $questionRow) {
                $question_owner = new User;
                $question_owner->getUserInfoFromDatabase($questionRow['question_owner']);
                $viewAnswers = new Answer;
                $answerslist = $viewAnswers->getAnswersOfQuestion($questionRow['question_id']);

                $counter++;
                $counter1++;
                if (count($answerslist) != 0) {
                    $counterA = count($answerslist);
                } else {
                    $counterA = 0;
                }
                $counter1++;
                $counter++;

                ?>

                <div class="question&A">

                    <div class="card mb-4">

                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-2 questionborder">
                                    <img src="img/profile-pictures.png" class="profile-img">
                                    <div class="username"><a
                                                href="viewProfile.php?user_id=<?= $questionRow['question_owner']; ?>"><b
                                                    class="usernameEdited1"><?= $question_owner->getUsername(); ?></b></a>

                                        <div class="starsQuestion">
                                            <div class="ext-center">

                                                <div class="review-block-rate">

                                                    <span class="fa fa-star" aria-hidden="true"></span>

                                                    <span class="fa fa-star" aria-hidden="true"></span>

                                                    <span class="fa fa-star" aria-hidden="true"></span>

                                                    <span class="fa fa-star" aria-hidden="true"></span>

                                                    <span class="fa fa-star" aria-hidden="true"></span>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-10">

                                    <h2 class="card-title" style="font-size: 30px;"><?= $questionRow['question_title']; ?></h2>
                                    <p class="card-text" style="font-size: 25px;"><?= $questionRow['question_body']; ?> </p>
                                        <h5 class="text-muted modified-text"> Tags:
                                            <?php
                                                $view_question_tags = new Questions_Tags;
                                                $tags_list = $view_question_tags->getTagsByQuestion($questionRow['question_id']);
                                                if ($tags_list){
                                                    foreach ($tags_list as $tagQ){
                                                        $getTag = new Tag;
                                                        $getTag->getTagInfoFromDatabaseById($tagQ['tag']);
                                                        echo "<span class=\"badge badge-primary \">".$getTag->getTag_text()."</span>";
                                                    }
                                                }

                                            ?>
                                        </h5>
                                </div>
                            </div>

                            <div class="card-footer text-muted cardfooteredited ">

                                <!-- #Answer1 bi href=#Answer1 hayeh bt2sheer ( tla3 3a tani comment ) -->

                                <a data-toggle="collapse" href="#Answer<?= $counter; ?>" aria-expanded="false"
                                   aria-controls="Answer#Answer<?= $counter; ?>">
                                    <i class="fa fa-commenting-o fa-2x" style="padding-left: 5px"
                                       aria-hidden="true"></i></a> <i class="fa fa-thumbs-o-up fa-2x"
                                                                      style="padding-left: 20px" aria-hidden="true"></i>
                                <span class="text-muted pull-right"
                                      style="padding-left: 10px;">  <?= count($answerslist); ?> Comments</span> &nbsp;
                                <span class="text-muted pull-right">10 Likes</span>

                            </div>

                        </div>
                        <br>

                        <!-- hayo hon 3al ID hada lazem t3meel counter w yzeed wa7d zay ma sawet bi Q&A V1 -->
                        <div class="collapse answercard11" id="Answer<?= $counter; ?>">
                            <div class="edited-input-filed-comments">
                                <div class="row firstrowOfAnswers">

                                    <div class="col-md-1">
                                        <img src="img/profile-pictures.png" class="input-profile-image">
                                    </div>
                                    <div class="col-md-10">
                                        <form id="Commentss" action="postAnswer.php" method="POST" >

                                            <input type="text" name="answer_text" id="inputbox">

                                            <input type="hidden" id="question_id" name="question_id"
                                                   value="<?= $questionRow['question_id']; ?>"/>
                                            <input type="hidden" id="user_id" name="user_id"
                                                   value="<?= $_SESSION['user_id']; ?>"/>


                                        </form>
                                    </div>
                                </div>


                            </div>


                            <div class="row commentsrow">

                                <?php
                                if ($answerslist) {
                                    foreach ($answerslist as $answer) {

                                        $answer_owner = new User;
                                        $answer_owner->getUserInfoFromDatabase($answer['answer_owner']);

                                        $viewComments_answer = new Comment_Answer;
                                        $commentsList = $viewComments_answer->fetchCommentsOfAnswer($answer['answer_id']);


                                        ?>

                                        <div class="hello">
                                            <br>
                                            <div class="row">
                                                <div class="col-md-1 space">
                                                    <img src="img/profile-pictures.png" class="input-profile-image1">
                                                </div>


                                                <div id="ratingbox1">
                                                    <div class="col-md-11">

                                                        <a href="#"><b style="font-size: 24px;"> <a
                                                                        href="#"><?= $answer_owner->getUsername(); ?>
                                                            </b></a> <?= $answer['answer_text']; ?>  </div>

                                                </div>


                                            </div>

                                        </div>


                                        <?php
                                            if ($commentsList){
                                                foreach ($commentsList as $commentRow) {
                                                    $comment_owner = new User;
                                                    $comment_owner->getUserInfoFromDatabase($commentRow['comment_owner']);
                                                    $randomNum = rand();


                                                    ?>
                                                    <div class="row">


                                                        <div class="reply"
                                                             style="margin-left:110px;font-size: 16px; margin-top: 3px;">
                                                            <!-- comments answer-->
                                                            <a data-toggle="collapse" class="float-left"
                                                               href="#AnswersComments<?= $randomNum; ?>"
                                                               aria-expanded="false"
                                                               style="padding-right:5px;"
                                                               aria-controls="#AnswersComments<?= $randomNum; ?>">Reply </a>
                                                            <div class="text-muted float-left"> 5 Comments</div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="collapse answercard11"
                                                                 id="AnswersComments<?= $randomNum; ?>">
                                                                <div class="row"
                                                                     style="margin-left: 50px; margin-top: 10px;">

                                                                    <div class="row">
                                                                        <div class="col-md-1 space">
                                                                            <img src="img/profile-pictures.png"
                                                                                 style="height: 35px;width: 35px;margin-left: 30px;margin-top: 10px;">
                                                                        </div>


                                                                        <div id="ratingbox3"
                                                                             style="  border: 2px solid #f7f7f7;padding: 10px;background-color: #f7f7f7;border-radius: 25px;width: 600px;margin-bottom: 20px;">
                                                                            <div class="col-md-11">

                                                                                <a href="#"><b style="font-size: 20px;">
                                                                                        <a
                                                                                                href="#"><?= $comment_owner->getUsername(); ?>
                                                                                    </b></a><?= $commentRow['comment_text']; ?>
                                                                            </div>

                                                                        </div>


                                                                    </div>

                                                                </div>


                                                                <div class="row" style="margin-left: 50px;">

                                                                    <div class="col-md-1">

                                                                        <img src="img/profile-pictures.png"
                                                                             style="height: 35px;width: 35px;margin-left: 20px;">

                                                                    </div>

                                                                    <div class="col-md-11">
                                                                        <form action="postCommentForAnswer.php"
                                                                              method="post">
                                                                            <input type="text" name="comment_text"
                                                                                   value=""
                                                                                   placeholder="Write a comment.."
                                                                                   class="removehighlight"
                                                                                   style="width:600px;  border-radius: 25px;background-color: #f7f7f7;padding-bottom: 12px;height: 40px;font-size: 17px;outline-width: 0;">
                                                                            <input type="hidden" name="answer_id"
                                                                                   value="<?= $answer['answer_id'] ?>">
                                                                            <input type="hidden"
                                                                                   value="<?= $_SESSION['user_id']; ?>"
                                                                                   name="user_id">


                                                                        </form>

                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }

                                            }else{
                                                // if there is no comment
                                                $randomNum = rand();

                                                ?>
                                                <div class="row">

                                                    <div class="reply"
                                                         style="margin-left:110px;font-size: 16px; margin-top: 3px;">
                                                        <!-- comments answer-->
                                                        <a data-toggle="collapse" class="float-left"
                                                           href="#AnswersComments<?= $randomNum; ?>" aria-expanded="false"
                                                           style="padding-right:5px;"
                                                           aria-controls="#AnswersComments<?= $randomNum; ?>">Reply </a>
                                                        <div class="text-muted float-left"> 5 Comments</div>
                                                    </div>


                                                    <div class="row">
                                                        <div class="collapse answercard11"
                                                             id="AnswersComments<?= $randomNum; ?>">

                                                            <div class="row" style="margin-left: 50px;">

                                                                <div class="col-md-1">

                                                                    <img src="img/profile-pictures.png"
                                                                         style="height: 35px;width: 35px;margin-left: 20px;">

                                                                </div>

                                                                <div class="col-md-11">
                                                                    <form action="postCommentForAnswer.php" method="post">
                                                                        <input type="text" name="comment_text"
                                                                               value="" placeholder="Write a comment.." class="removehighlight"
                                                                               style="width:600px;  border-radius: 25px;background-color: #f7f7f7;padding-bottom: 12px;height: 40px;font-size: 17px;outline-width: 0;">
                                                                        <input type="hidden" name="answer_id" value="<?= $answer['answer_id'] ?>">
                                                                        <input type="hidden" value="<?= $_SESSION['user_id']; ?>" name="user_id">
                                                                        <input type="submit" value="Submit Comment" name="submitComment">

                                                                    </form>

                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        ?>

                                        <?php
                                    }
                                }
                            ?>


                            </div>
                            <br>
                            <br>
                        </div>

                    </div>

                </div>


                <?php
            }
        }
    }
}
?>

</div>
