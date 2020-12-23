// dans cet objet Main j'appele toutes mes classes

class Main
 {
  constructor() {
    this.map = new Gmap();
    this.map.initMap();
  }
}

class Gmap 
{ 
  // apparition de la map leaflet
    initMap() {
    const lat = 46.413340;
    const long = 1.788320;
    const bounds = [lat, long];
    let mymap;// je crée une variable vide au début

        // cette requete va me permettre de transformer des villes en lat et long
        $(document).ready(function(){ 
          mymap = L.map('map').setView([lat, long], 9);
          L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoicG9sdnUiLCJhIjoiY2s0c3FmY2FoMTFzMDNlcXVmeXZhdGR1YiJ9.XDjMZFILlUhTvOnBqMAucg', {
          attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
          maxZoom: 5,
          minZoom: 5,
          maxBounds: bounds,
          id: 'mapbox/streets-v11',
          }).addTo(mymap);
          let icone =  L.icon({ // creation des icones
            iconUrl: '/images/marker3.png',
            iconSize: [50, 50],
            iconAnchor: [25, 50],
            popupAnchor: [-3, -76],
          });
// Si je ne mets pas le / devant c'est mort (2 jours de galère)
  let url = "/members.json";
 // requete qui permet de récuperer les inos du membre
 $.ajax({
  url : url,
  type : 'GET',
 
  dataType : 'json',

  success:function(response){

    const req = response;
  
      let city =req[0]["location"];
    console.log(city)
          
          // api oms
           $.ajax({
            url: "https://nominatim.openstreetmap.org/search", // URL de Nominatim
            type: 'get', // Requête de type GET
            data: "q="+city+"&format=json&addressdetails=1&limit=1&polygon_svg=1" // Données envoyées (q -> adresse complète, format -> format attendu pour la réponse, limit -> nombre de réponses attendu, polygon_svg -> fournit les données de polygone de la réponse en svg)
            }).done(function (response) {
            if(response != ""){
              let lat = response[0]['lat'];
              let lon = response[0]['lon'];
              L.marker([lat, lon], {icon: icone}).addTo(mymap);// creation du marker
            }});// fin ajax openstreetmap
        }
      })
    })      
  }// fin initmap     
}