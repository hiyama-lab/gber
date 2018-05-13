function logout(){
    $.ajax({
        type: 'POST',
        url: baseurl+'model/logout.php',
        timeout: 10000,
        done: window.location.href = "login.php"
    })
}