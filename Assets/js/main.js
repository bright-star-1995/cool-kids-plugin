document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('#login-but').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var email = document.getElementById('login-email').value;
            if(validateEmail(email)) {
                fetch(urlVars.ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'authenticate',
                        email: email,
                        type: 'login',
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok. Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = urlVars.homeurl; // Redirect or refresh page
                    } else {
                        document.getElementById('login-err').innerHTML = 'No Email Registered';
                    }
                });
            } else {
                alert('Email is not valid')
            }
        });
    });
    
    document.querySelectorAll('#register-but').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('register-err').innerHTML = '';
            var email = document.getElementById('register-email').value;
            if(validateEmail(email)) {
                fetch(urlVars.ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'authenticate',
                        email: email,
                        type: 'register',
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok. Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('register-err').innerHTML = 'Successful! You can register another.';
                        document.getElementById('register-email').value = '';
                    } else {
                        document.getElementById('register-err').innerHTML = data.error;
                    }
                });
            }
        });
    });
    
    document.querySelectorAll('#logout-link').forEach(function(link) {
        link.addEventListener('click', function() {
            fetch(urlVars.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'logout',
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = urlVars.homeurl; // Redirect or refresh page
                } else {
                    throw new Error('Failed to log out. Server responded with an error.');
                }
            })
            .catch(error => {
            })
            .finally(() => {
            });
        });
    });
});

const validateEmail = (email) => {
  return email.match(
    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
};
