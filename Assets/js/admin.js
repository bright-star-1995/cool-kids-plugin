document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.update-role-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var user_email = this.getAttribute('data-email');
            var firstname = this.getAttribute('data-firstname');
            var lastname = this.getAttribute('data-lastname');
            var user_id = this.getAttribute('data-id');
            var role = document.getElementById('role-' + user_id).value;
            var nonce = document.getElementById('nonce').value;
            fetch(adminUrlVars.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-WP-Nonce': nonce,
                },
                body: JSON.stringify({
                    role: role,
                    email: user_email,
                    firstname: firstname,
                    lastname: lastname,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok. Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                alert(data.response);
                if (data.status == '200') {
                    window.location.href = ''; // Redirect or refresh page
                }
            });
        });
    });
});