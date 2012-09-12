<?php
        error_reporting(E_ALL | E_STRICT) ;
        ini_set('display_errors', 'On');
        
require_once('classes/comments.php');
require_once('template/template.php');

function redirect($url) {
    header("Location: {$url}");
    exit;
}

$comments = new Comments();
$errors = array();
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $newComment = new Comment();
    $newComment->author = trim(htmlspecialchars($_POST['authorname']));
    $newComment->email = trim(htmlspecialchars($_POST['authoremail']));
    $newComment->comment = nl2br(trim(htmlspecialchars($_POST['comment'])));
    $errors = $newComment->validate();
    if (!is_array($errors)) {
        $replyto = htmlspecialchars($_POST['replyto']);
        if (!$replyto) $replyto = FALSE;
        $id = $comments->insertComment($newComment,$replyto);
        redirect("/index.php#comment_{$id}");
    }
} 
$commentList = $comments->getComments();

$tpl = new Template(array('comments' => $commentList->comments, 'errors' => $errors));
$tpl->render('comments.phtml');


