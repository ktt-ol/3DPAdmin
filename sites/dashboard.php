<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-4">
            <h2>Printer-Status</h2>
            <hr/>
            <div class="boxed">
            <?php get_printer_status(); ?>
            </div>
        </div>
        <div class="col-4">
            <h2>Filament-Status</h2>
            <hr/>
            <div class="boxed table-responsive">
                <table id="tablePreview" class="table table-sm">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Color</th>
                      <th>Weight</th>
                      <th>&#8709</th>
                      <th>Owner</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php get_filament_status(); ?>
                  </tbody>
                </table>            
            </div>
        </div>
        <div class="col-4">
            <h2>Last-Prints</h2>
            <hr/>
            <div class="boxed table-responsive">
                <table id="tablePreview" class="table table-sm">
                  <thead>
                    <tr>
                      <th>Operator</th>
                      <th>Printer</th>
                      <th>Weight</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php get_lastprint_status(); ?>
                  </tbody>
                </table>            
            </div>
        </div>
    </div>    
</div>
        