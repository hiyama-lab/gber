function logout(token){
    $.ajax({
        type: 'POST',
        url: baseurl+'model/logout.php?token='+token,
        timeout: 10000,
        done: window.location.href = "login.php"
    })
}