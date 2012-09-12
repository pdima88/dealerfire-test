function showForm(commentId) {
    document.getElementById('footer').style.display = 'none';
    if (commentId) {
        document.getElementById('form_placeholder').style.display = 'none';
        document.getElementById('form_replyto').setAttribute('value', commentId);
        document.getElementById('form_placeholder_'+commentId).appendChild(document.getElementById('addcomment'));
        location.href='#comment_'+commentId;
    } else {
        document.getElementById('form_placeholder').style.display = 'block';
        document.getElementById('addcomment').parentNode = document.getElementById('form_placeholder_');
        document.getElementById('form_replyto').setAttribute('value','');
        document.getElementById('form_placeholder_').appendChild(document.getElementById('addcomment'));
        location.href='#new';
        
    }
    
    
}

function hideForm() {
    document.getElementById('form_placeholder_').appendChild(document.getElementById('addcomment'));
    document.getElementById('form_placeholder').style.display = 'none';
    document.getElementById('footer').style.display = 'block';
}

function body_onload() {
    document.getElementById('form_placeholder').style.display = 'none';
}