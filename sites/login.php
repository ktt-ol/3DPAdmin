<form action="/php/login.php" method="post" class="form-horizontal">
    <fieldset>
        <div class="form-group">
          <label class="col-md-4 control-label" for="username">Username</label>  
          <div class="col-md-4">
          <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="">
          </div>
        </div>
        <div class="form-group">
          <label class="col-md-4 control-label" for="password">Password</label>
          <div class="col-md-4">
            <input id="password" name="password" type="password" placeholder="" class="form-control input-md" required="">
          </div>

        </div>
        <!-- Button -->
        <div class="form-group">
          <div class="col-md-4">
            <button id="singlebutton" name="singlebutton" class="btn btn-primary">Login</button>
          </div>
        </div>
    </fieldset>
</form>