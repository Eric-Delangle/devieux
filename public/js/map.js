

// DEBUT DE MES SCRIPTS
// dans cet objet j'appele toutes mes classes

class Main
 {
  constructor() {
    this.map = new Gmap();
    this.map.initMap();
  }
}

// création de la classe Gmap
class Gmap 
{ 
  constructor () {
   
   // this.infoMember = document.getElementById('infoMember');
   // this.nomMembre = document.getElementById('nomMembre');
   // this.ville = document.getElementById('ville');
  //  this.categorie = document.getElementById('categorie');
   // this.message = document.getElementById('message');
    //this.markers = [];
  }

  // apparition de la map leaflet
    initMap() {
    const lat = 46.413340;
    const long = 1.788320;
    const bounds = [lat, long];
    let mymap;// je crée une variable vide au début
   console.log(lat)
        // cette requete va me permettre de transformer des villes en lat et long
        $(document).ready(function(){ 
          mymap = L.map('map').setView([lat, long], 5);
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

          console.log('haha')

        


        })   
        
    }// fin initmap     
}