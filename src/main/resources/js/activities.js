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


// Filter on date or on appointment made date
function filterOn() {
    var sort_by_name = function (a, b) {
        return a.innerHTML.toLowerCase().localeCompare(b.innerHTML.toLowerCase());
    }

    var listUpcoming = $("#accordion-upcoming > .list-group-item").get();
    var listUpcomingDetails = $("#accordion-upcoming > .details").get();
    listUpcoming.sort(sort_by_name);
    for (var i = 0; i < listUpcoming.length; i++) {
        listUpcoming[i].parentNode.appendChild(listUpcoming[i]);
        //add correct details to element
        for (var y = 0; y < listUpcomingDetails.length; y++)
        if ($(listUpcoming[i]).attr('aria-controls') === $(listUpcomingDetails[y]).attr('id') ){
            listUpcoming[i].parentNode.appendChild(listUpcomingDetails[y]);
        }
    }
    var listPrevious = $("#accordion-previous > .list-group-item").get();
    var listPreviousDetails = $("#accordion-previous > .details").get();
    listPrevious.sort(sort_by_name);
    for (var i = 0; i < listPrevious.length; i++) {
        listPrevious[i].parentNode.appendChild(listPrevious[i]);
        //add correct details to element
        for (var y = 0; y < listPreviousDetails.length; y++)
        if ($(listPrevious[i]).attr('aria-controls') === $(listPreviousDetails[y]).attr('id')) {
            listPrevious[i].parentNode.appendChild(listPreviousDetails[y]);
        }
    }
}