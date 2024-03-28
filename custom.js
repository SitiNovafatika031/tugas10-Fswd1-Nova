$(document).ready(function() {
    loadUserTable();

    function loadUserTable() {
        $.ajax({
            url: 'fetch_users.php', // Ganti dengan URL file PHP yang akan mengambil data pengguna
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#userTableContainer').html(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
});
