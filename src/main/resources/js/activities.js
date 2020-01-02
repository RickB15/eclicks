$(function(){
    $('#upcoming-bar').click(function () {
        $('#upcoming-list').toggleClass('hidden');
        $('#icon-upcoming').toggleClass('fa-plus fa-minus');
    });
    $('#previous-bar').click(function () {
        $('#previous-list').toggleClass('hidden');
        $('#icon-previous').toggleClass('fa-plus fa-minus');
    });
    $('#update_previous_form').submit(function (event) {
        if (!confirm('Are you sure you want to delete this appointment permanently?') ) {
            event.preventDefault();
        }
    });
    $('#update_form').submit(function (event) {
        if (!confirm("Are you sure you want to cancel this appointment? This can't be undone!")) {
            event.preventDefault();
        }
    });
});

// For the filter search
function search(input) {
    var filter, li, i, txtValue;
    filter = input.value.toUpperCase();
    // ul = document.getElementById("userListUp");
    li = document.getElementsByClassName("searchable");
    for (i = 0; i < li.length; i++) {
        txtValue = li[i].textContent || li[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
// Remove content from filter when esc or cross is pressed
function searchChange() {
    var li = document.getElementsByClassName("searchable");
    for (i = 0; i < li.length; i++) {
        li[i].style.display = "";
    }
}