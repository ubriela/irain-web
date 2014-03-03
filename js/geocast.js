var map = null;
var infoWindow;
var isIE;

google.maps.event.addDomListener(window, 'load', init);
var allMarkers = [];

var cellPolygons = new Array();
var cells = new Array();
//var polygon = new Array();
var point0;
var point1;
var point2;
var point3;

var cellIdx = -1;
var json = "blank";

function load() {
    map = new google.maps.Map(document.getElementById("map_canvas"), {
        center: new google.maps.LatLng(37.76822, -122.44297),
        zoom: 12,
        mapTypeId: 'roadmap'

    });
    google.maps.event.addListener(map, 'dblclick', function(event) {

        var touch_point = new google.maps.LatLng(event.latLng.lat(),
                event.latLng.lng());
        var marker = new google.maps.Marker({
            map: map,
            position: touch_point,
            icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
        });
        infoWindow = new google.maps.InfoWindow;
        bindInfoWindow(marker, map, infoWindow, event.latLng.lat() + '-'
                + event.latLng.lng());
        marker.setMap(map);
        allMarkers.push(marker);
    });

}

/*GeoCast_Query  takes as parametter the url which is used to retrieve 
 *a json file containning information of the geocast query
 */
function retrieveGeocastInfo(url) {
    $.getJSON('http://whateverorigin.org/get?url=' + encodeURIComponent(url)
            + '&callback=?', function(data) {
                json = data.contents;

                if (json === "blank")
                    alert("Crowdsourcing service is now unavailable");
                else {
                    obj = JSON.parse(json);
                    if (obj.hasOwnProperty('error')) {
                        alert("The selected location is outside of the dataset");
                    } else {
                        drawGeocastRegion();
                    }
                }
            });
}

/*
 *Overlay_GeoCast_Region is to visualize how geocast cells are chosen by
 *iteratively overlay polygons on map.
 *This function used setInterval to repeatedly add cell after specific 
 *amount of miliseconds
 */
function drawGeocastRegion() {
    var i = -1;
    var interval = setInterval(function() {
        drawGeocastCell(i);
        i++;
        if (i >= obj.geocast_query.x_min_cords.length)
            clearInterval(interval);
    }, 100);
}

/*
 * add_geocast_cell is to add a specific cell in the list. It takes
 * as a parameter a number i indicating the order of the cell in the cell list.
 * The eventlistenr at the bottom of the function is to display a cell info
 * whenever it is clicked. This is called within the Overlay_GeoCast_Region function
 */
function drawGeocastCell(i) {
    polygon = new Array();

    point0 = new google.maps.LatLng(obj.geocast_query.x_min_cords[i],
            obj.geocast_query.y_min_cords[i]);
    polygon[0] = point0;

    point1 = new google.maps.LatLng(obj.geocast_query.x_min_cords[i],
            obj.geocast_query.y_max_cords[i]);
    polygon[1] = point1;

    point2 = new google.maps.LatLng(obj.geocast_query.x_max_cords[i],
            obj.geocast_query.y_max_cords[i]);
    polygon[2] = point2;

    point3 = new google.maps.LatLng(obj.geocast_query.x_max_cords[i],
            obj.geocast_query.y_min_cords[i]);
    polygon[3] = point3;

    polygon[4] = point0;

    cellIdx += 1;
    cells[cellIdx] = polygon;
    cellPolygons[cellIdx] = new google.maps.Polygon({
        path: cells[cellIdx],
        strokeColor: "#0000FF",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#0000FF",
        fillOpacity: 0.1
    });
    cellPolygons[cellIdx].setMap(map);

    // Add a listener for the click event to show cell info.
    infoWindow = new google.maps.InfoWindow();
    google.maps.event.addListener(cellPolygons[cellIdx], 'click',
            function(event) {
                var info = 'Order added: ' + (i + 1);
                info += '</br><b>Cell Utility:</b>'
                        + obj.geocast_query.utilities[i][0];
                info += '</br><b>Current GeoCast Utility:</b>'
                        + obj.geocast_query.utilities[i][1];
                info += '</br><b>Compactness:</b>'
                        + obj.geocast_query.compactnesses[i];
                info += '</br><b>Distance:</b>'
                        + obj.geocast_query.distances[i];
                info += '</br><b>Area:</b>' + obj.geocast_query.areas[i];
                info += '</br><b>Worker Counts:</b>'
                        + obj.geocast_query.worker_counts[i];

                infoWindow.setContent(info);
                infoWindow.setPosition(event.latLng);

                infoWindow.open(map);
            });

}

/*The following function is to specify action to be performed when an event
 *happened on a marker.
 *
 *When a marker is clicked, the geocast_query for the task the marker
 *represent will be issued and visuallized on map 
 */
function drawATask(marker, map, infoWindow, html) {
    json = "blank";
    google.maps.event.addListener(marker, 'mouseover', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
    });
    google.maps.event.addListener(marker, 'mouseout', function() {
        infoWindow.close(map, marker);
    });
    google.maps.event.addListener(marker, 'click', function(event) {
        url = 'http://geocrowd2.cloudapp.net/geocast/' + event.latLng.lat()
                + "," + event.latLng.lng();
        retrieveGeocastInfo(url);
        alert('Lat: ' + event.latLng.lat() + ' and Longitude is: '
                + event.latLng.lng());
        var center = new google.maps.LatLng(event.latLng.lat(), event.latLng
                .lng());
        map.panTo(center);
    });
}

/*
 * Show_Boundary is to show/hide boundary of the dataset
 */
var boundary;
var bound_vertices = new Array();
function showBoundary() {
    var button = document.getElementById("boundary");
    var json_boundary;
    var url1 = 'http://geocrowd2.cloudapp.net/dataset';

    if (button.value == "Show Boundary") {
        $.getJSON('http://whateverorigin.org/get?url='
                + encodeURIComponent(url1) + '&callback=?', function(data) {
            json_boundary = data.contents;
            boundary = JSON.parse(json_boundary);
            button.value = "Hide Boundary";
            bound_vertices[0] = new google.maps.LatLng(
                    boundary.boundaries[0], boundary.boundaries[1]);
            bound_vertices[1] = new google.maps.LatLng(
                    boundary.boundaries[0], boundary.boundaries[3]);
            bound_vertices[2] = new google.maps.LatLng(
                    boundary.boundaries[2], boundary.boundaries[3]);
            bound_vertices[3] = new google.maps.LatLng(
                    boundary.boundaries[2], boundary.boundaries[1]);

            bound_vertices[4] = new google.maps.LatLng(
                    boundary.boundaries[0], boundary.boundaries[1]);
            boundary = new google.maps.Polyline({
                path: bound_vertices,
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2

            });
            boundary.setMap(map);
            infoWindow = new google.maps.InfoWindow();
            google.maps.event.addListener(boundary, 'click', function(
                    event) {
                var boundaryinfo = 'dataset:<br>' + boundary.dataset
                        + '<br>workers counts:<br>' + boundary.worker_count;
                infoWindow.setContent(boundaryinfo);
                infoWindow.setPosition(event.latLng);
                infoWindow.open(map);
            });
        });

    } else {
        button.value = "Show Boundary";
        boundary.setMap(null);
    }

}

/*
 * This is for 1first function: input coordinates to a text box, hit button
 * and visualize
 * Query function is trigerred when the GeoCastQuery button is clicked.
 * It takes the coordinate input by the users and then visualize geocast query
 * for task at that specific location
 */
function drawTestTask() {
    url = 'http://geocrowd2.cloudapp.net/geocast/'
            + document.forms["input"]["coordinate"].value;
    retrieveGeocastInfo(url);

    var coor = document.forms["input"]["coordinate"].value;
    var lat_lng = coor.split(",");
    var task_point = new google.maps.LatLng(lat_lng[0], lat_lng[1]);
    var marker = new google.maps.Marker({
        map: map,
        position: task_point,
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
    });
    var infoWindow = new google.maps.InfoWindow;
    drawATask(marker, map, infoWindow,
            document.forms["input"]["coordinate"].value);
    marker.setMap(map);
    allMarkers.push(marker);
    map.panTo(task_point);
}

/*
 *this is for the 2nd function: select a task from dropdown list,
 *hit button and visuallize
 * Visualize_Task_Seleclted is triggered when user click on Visualize
 * button after choosing a coordinate from a dropdown list.
 * The function will then visualize geocast query
 * for task at the selected location
 */
function drawSelectedTask(latlng) {
    url = 'http://geocrowd2.cloudapp.net/geocast/' + latlng;
    retrieveGeocastInfo(url);

    var lat_lng = latlng.split(",");
    var task_point = new google.maps.LatLng(lat_lng[0], lat_lng[1]);
    var marker = new google.maps.Marker({
        map: map,
        position: task_point,
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
    });
    var infoWindow = new google.maps.InfoWindow;
    drawATask(marker, map, infoWindow,
            document.forms["input"]["coordinate"].value);
    marker.setMap(map);
    allMarkers.push(marker);
    map.panTo(task_point);
}

function clearMap() {
    for (var n = 0; n < allMarkers.length; n++)
        allMarkers[n].setMap(null);
    allMarkers = [];

    for (var n = 0; n < cellPolygons.length; n++)
        cellPolygons[n].setMap(null);
    cellPolygons = [];
    
    cellIdx = -1;
}

/**
 * The task tab on left
 * @returns {undefined}
 */
$(function() {
    $("#tabs").tabs();
});


/***
 * create table to store history task list
 * @returns {undefined}
 */
function init() {
    completeTable = document.createElement("table");
    completeTable.setAttribute("class", "popupBox");
    completeTable.setAttribute("style", "display: true");
    autoRow = document.getElementById("auto-row");
    autoRow.appendChild(completeTable);

    retrieveHistoryTasks();
}

/**
 * query history task, call geocast/tasks function
 * @returns {undefined}
 */
function retrieveHistoryTasks() {
    $.ajax({
        url: 'tasks',
        data: '',
        type: "GET",
        dataType: "xml",
        success: callbackTasks
    });
}

// populate spreadsheet
function callbackTasks(responseXML) {

    // right table
    clearTable();
    parseTasksFromXML(responseXML);
}

function clearTable() {
    if (completeTable.getElementsByTagName("tr").length > 0) {
        completeTable.style.display = 'none';
        for (loop = completeTable.childNodes.length - 1; loop >= 0; loop--) {
            completeTable.removeChild(completeTable.childNodes[loop]);
        }
    }
}

/**
 * get lat/lng pairs from xml file
 * @param {type} responseXML
 */        
function parseTasksFromXML(responseXML) {

    // no matches returned
    if (responseXML === null) {
        return false;
    } else {

        var tasks = responseXML.getElementsByTagName("tasks")[0];

        if (tasks.childNodes.length > 0) {
            completeTable.setAttribute("bordercolor", "black");
            completeTable.setAttribute("border", "1");

            for (loop = 0; loop < tasks.childNodes.length; loop++) {
                var task = tasks.childNodes[loop];
                var lat = task.getElementsByTagName("lat")[0].childNodes[0].nodeValue;
                var lng = task.getElementsByTagName("lng")[0].childNodes[0].nodeValue;
                appendTask(lat, lng)
            }
        }
    }
}

/**
 * append a task to task table
 * 
 * @param {type} lat
 * @param {type} lng
 * @returns {undefined}
 */
function appendTask(lat, lng) {

    var row;
    var cell;
    var linkElement;

    if (isIE) {
        completeTable.style.display = 'block';
        row = completeTable.insertRow(completeTable.rows.length);
        cell = row.insertCell(0);
    } else {
        completeTable.style.display = 'table';
        row = document.createElement("tr");
        cell = document.createElement("td");
        row.appendChild(cell);
        completeTable.appendChild(row);
    }

    cell.className = "popupCell";

    linkElement = document.createElement("a");
    linkElement.className = "popupItem";
    linkElement.setAttribute("href", "javascript:drawSelectedTask('" + lat + "," + lng + "')");
    linkElement.setAttribute("onClick", "changeLinkColor(this)");
    linkElement.appendChild(document.createTextNode(lat + "," + lng));
    cell.appendChild(linkElement);
}

var currentLink = null;
/**
 * change color of a task link when click on it
 * @param {type} link
 * @returns {undefined}
 */
function changeLinkColor(link) {
    if (currentLink !== null) {
        currentLink.style.color = link.style.color; //You may put any color you want
        currentLink.style.fontWeight = link.style.fontWeight;
        ;
    }
    link.style.color = 'blue';
    link.style.fontWeight = 'bold';
    currentLink = link;
}