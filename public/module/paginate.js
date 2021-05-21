function firstLink(count, path) {

    if(count > 10) {

        var defaultTarget = document.getElementById("paging_links");

        var defaultLink = document.createElement("span");

        defaultLink.innerHTML = "<a class='first_page_link' href="+ path +">&lt;</a>";

        defaultTarget.appendChild(defaultLink);
    }

}

function linksLoop(nbr_links, path) {

    for(var i = 1; i < nbr_links; i++){

        var target = document.getElementById("paging_links");

        var pagingLink = document.createElement("span");

        pagingLink.innerHTML = "<a class='next_page_link' href='"+ path +"?start=" + i * 10 + "&limit=10'>" + i + "</a>";

        target.appendChild(pagingLink);

    }

}
