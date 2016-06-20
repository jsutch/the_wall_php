function get_comments() {
		// get messages
	$commentQuery="SELECT m.id as mid, concat(u.first_name, ' ', u.last_name) as name, c.comment,m.id as cmid, c.id as cid, date_format(m.updated_at, '%M %D %Y at %H:%m:%s') as mupdate, date_format(c.updated_at, '%M %D %Y at %H:%m:%s') as cupdate FROM users u, comments c, messages m WHERE u.id = c.user_id and c.message_id = m.id ORDER BY m.updated_at DESC;";

	$comment=fetch_all($commentQuery);
		foreach($comment as $key => $value){

			$strDate = strtotime($comment[$key]['cupdate']);
			$formattedDate = date("F jS Y h:i:s A", $strDate);
			// $name = $message[$key]['first_name'];

			$comments[] = "<div class='post'>\n" .
							"<h3><b>" . $comment[$key]['name'] . " " .  " - " . $formattedDate . "</b></h3>\n" .
							"<span class='posted'><p>" . $comment[$key]['comment'] . "</p></span>" .
							"</div>";
		}					
		foreach ($comments as $comment) {
			echo $comment . '<br>';
		}

}

function display_comments($message, $user_id)
{
    echo    "<div class='.comments'>";
    $comments_query = 
    $comments = fetch_all($comments_query); 
    foreach ($comments as $comment)
    {
        display_comment($comment, $user_id);
    }
    echo   "<form class='centered com-form' action='the.wall.process.php' method='post'>
                <label class='left'><p>Post a comment</p><textarea rows='3' class='com-box' name='comment'></textarea></label>
                <input type='hidden' name='action' value='add_comment'>
                <input type='hidden' name='msg_id' value='{$message['id']}'>
                <br />
                <input class='btn btn-success' type='submit' value='comment'>
            </form>
            </div>
        </div>";
}

function display_comment($comment, $user_id)
{
    $dateTime = strtotime($com['created_at']);
    $dateString = date("F jS, Y - g:i A", $dateTime);
    echo   "<div class='com-hdr'>{$com['first_name']} {$com['last_name']} - {$dateString}";

    $minutesSinceComment = (time() - $dateTime) / 60;
    if (    (($com['user_id'] == $user_id) && ($minutesSinceComment < 30))
        ||  ($user_id == ADMIN_ID))
    {
        echo    "<form class='del-btn-form' action='the.wall.process.php' method='post'>                
                    <input type='hidden' name='action' value='delete_comment'>
                    <input type='hidden' name='record_id' value='{$comment['id']}'>
                    <input type='submit' class='btn btn-warning' value='delete comment'>
                </form>";
    }
    echo "</div><p class='comments'>{$com['comment_txt']}</p>";
}