
var callsLabelData = [];
var callsCallData = [];
var kmsLabelData = [];
var kmsCallData = [];
//var labeldata = ["Mandag","Tirsdag","Onsdag","Torsdag","Fredag"];

window.onload = function(){
  if (callsCallData.length === callsLabelData.length){
    
    var lineData = {
      labels: callsLabelData,
      datasets: [
        {
          label: "Orderdata",
          fillColor: "rgba(26,179,148,0.5)",
          strokeColor: "rgba(26,179,148,0.7)",
          pointColor: "rgba(26,179,148,1)",
          pointStrokeColor: "#fff",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(26,179,148,1)",
          data: callsCallData
        } 
      ]
    };

    var lineOptions = {
      scaleShowGridLines: true,
      scaleGridLineColor: "rgba(0,0,0,.05)",
      scaleGridLineWidth: 1,
      bezierCurve: true,
      bezierCurveTension: 0.4,
      pointDot: true,
      pointDotRadius: 4,
      pointDotStrokeWidth: 1,
      pointHitDetectionRadius: 20,
      datasetStroke: true,
      datasetStrokeWidth: 2,
      datasetFill: true,
      responsive: true
    };

    var ctx = document.getElementById("callsperday").getContext("2d");
    var callsperday = new Chart(ctx).Line(lineData, lineOptions);
  }
  
  if (kmsCallData.length === kmsLabelData.length){
    
    var lineData2 = {
      labels: kmsLabelData,
      datasets: [
        {
          label: "Orderdata",
          fillColor: "rgba(28,132,198,0.5)",
          strokeColor: "rgba(28,132,198,0.7)",
          pointColor: "rgba(28,132,198,1)",
          pointStrokeColor: "#fff",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(28,132,198,1)",
          data: kmsCallData
        } 
      ]
    };

    var lineOptions2 = {
      scaleShowGridLines: true,
      scaleGridLineColor: "rgba(0,0,0,.05)",
      scaleGridLineWidth: 1,
      bezierCurve: true,
      bezierCurveTension: 0.4,
      pointDot: true,
      pointDotRadius: 4,
      pointDotStrokeWidth: 1,
      pointHitDetectionRadius: 20,
      datasetStroke: true,
      datasetStrokeWidth: 2,
      datasetFill: true,
      responsive: true
    };

    var ctx = document.getElementById("kmsperday").getContext("2d");
    var kmsperday = new Chart(ctx).Line(lineData2, lineOptions2);
  }

};