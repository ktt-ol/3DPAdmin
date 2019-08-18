int arraylength = 4;
int slider[arraylength];
int output[arraylength];

var slider[0] = document.getElementById("quality");
var output[0] = document.getElementById("value_quality");

var slider[1] = document.getElementById("duty");
var output[1] = document.getElementById("value_duty"); 

sliderslider[0].oninput = function() {
  outputoutput[0].innerHTML = this.value;
}
sliderslider[1].oninput = function() {
  outputoutput[1].innerHTML = this.value;
}



for(int i = 0; i < arraylength; i++){
    output[i].innerHTML = slider[i].value;

}



