$(document).ready(function() {
    $('#signout').on('click', function(){
        $.post('', {signOut: true}, function(){
        })
    })
    $('#signinAdmin').on('click', function(){
        $.post('', {signOutAdmin: true}, function(){
            console.log('a');
        })
    })
})