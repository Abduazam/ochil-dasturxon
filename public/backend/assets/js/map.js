function showMap(lat, long)
{
    let coord = {lat: lat, lng: long};

    let map = new google.maps.Maps(
        document.getElementById("map"),
        {
            zoom: 10,
            center: coord,
        }
    );

    new google.maps.Marker({
        position: coord,
        map: map
    });
}

showMap(0, 0);
