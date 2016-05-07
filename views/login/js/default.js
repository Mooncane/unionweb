// login

window.onload = function(){
  
  document.getElementById('loginform').onsubmit = function(){
    
    var url = this.getAttribute("action");
    
    xhrPost(url, serialize(this), function(){
      if (http_request.readyState === 4 && http_request.status === 200){
        var response = http_request.responseText;
        if (response === "auth"){
          document.location.href = "index";
        } else {
          addText.error('Brugernavn eller password er ikke korrekt');
        }
        
      }
    });
        
    return false;
  };
  
  document.getElementById('password').value = '';
  
};

var addText = {};
addText.error = function(txt) {
  document.getElementById('errortext').innerHTML = "<div class='text-danger align-center'>"+txt+"</div>";
};
  


