function logout(){
    $.ajax({
        type: 'POST',
        data: {"out": true},
        url: baseurl+'model/sessioncheck.php',
        timeout: 10000,
        done: window.location.href = "login.php"
    })
}