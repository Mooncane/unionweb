<div class="loginwrapper">
  <h1 class="companyname">VTCare<sup>&reg;</sup></h1>
  <div class="login-welcome">
    <h2>Velkommen til VTCare<sup>&reg;</sup></h2>
    <p>
      Denne applikation er specielt udviklet til at arbejde optimalt sammen med Visitour ruteoptimeringssoftware.<br><br>Login ved at indtaste dit brugernavn og password herunder.
    </p>
  </div>
   
  <div class="login-form">
    <form id="loginform" action="login/authenticate" method="post">
      <div class="form-input-group">
        <input class="input" type="text" name="username" placeholder="Brugernavn" />
      </div>
      <div class="form-input-group">
        <input class="input" id="password" type="password" name="password" placeholder="Password" />
      </div>
      <input id="loginbutton" type="submit" class="btn btn-fullwidth btn-primary" value="Login" />
    </form>
    
    <br>
    <div class="login-forgot text-info align-center"><a href="#">Glemt password?</a></div>
    
  </div>
  <div id="errortext">
    
  </div>
</div>