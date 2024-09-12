document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally

    var username = document.getElementById('username').value.trim();
    var password = document.getElementById('password').value.trim();

    if (username === '' || password === '') {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please enter both username and password!',
        });
    } else {

        this.submit();
    }
});
