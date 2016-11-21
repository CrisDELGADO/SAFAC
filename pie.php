      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2016-2017 <a href="index.php">SAFAC</a>.</strong> Todos los Derechos Reservados.
      </footer>

     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
    
    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/select2.full.min.js"></script>
    <!-- Toggle -->
    <script src="bootstrap/js/bootstrap-toggle.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <!-- FLOT CHARTS -->
    <script src="plugins/flot/jquery.flot.min.js"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="plugins/flot/jquery.flot.resize.min.js"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="plugins/flot/jquery.flot.pie.min.js"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="plugins/flot/jquery.flot.categories.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <script src="toastr/toastr.min.js"></script>
    
    <!-- page script -->
    <script>

      function objetoAjax(){
        var xmlhttp=false;
        try {
          xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
          try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          } catch (E) {
            xmlhttp = false;
          }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
          xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
      }

        function flotante(contenedorID, flotanteID, tipo){
  
        if (tipo==1){
          //Si hacemos clic en abrir mostramos el fondo negro y el flotante
          $('#'+contenedorID).show();
            $('#'+flotanteID).animate({
              marginTop: "-152px"
            });
        }
        if (tipo==2){
          //Si hacemos clic en cerrar, deslizamos el flotante hacia arriba
            $('#'+flotanteID).animate({
              marginTop: "-756px"
            });
          //Una vez ocultado el flotante cerramos el fondo negro
          setTimeout(function(){
          $('#'+contenedorID).hide();
            
          },500)
        }
        if (tipo==3){
          //Si hacemos clic en abrir mostramos el fondo negro y el flotante
          $('#'+contenedorID).show();
          $('#'+flotanteID+'').animate({
            marginTop: "-152px"
          });
          setTimeout(function(){
            $('#'+flotanteID+'').animate({
              marginTop: "-756px"
            });
          },1000)
          
          setTimeout(function(){
            $('#'+contenedorID).hide(); 
          },2000)
        }

      } 


      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": false,
          "number":false,
          "autoWidth": true
        });

         //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });


      });

      //INTERACTIVE CHART
      

        var interactive_plot = $.plot("#interactive1", [getRandomData()], {
          grid: {
            borderColor: "#f3f3f3",
            borderWidth: 1,
            tickColor: "#f3f3f3"
          },
          series: {
            shadowSize: 0, // Drawing is faster without shadows
            color: "#3c8dbc"
          },
          lines: {
            fill: true, //Converts the line chart to area chart
            color: "#3c8dbc"
          },
          yaxis: {
            min: 0,
            max: 40,
            show: true
          },
          xaxis: {
            min: 0,
            max: 90,
            show: false
          }
        });

        
       

         var interactive_plot2 = $.plot("#interactive2", [getRandomData2()], {
          grid: {
            borderColor: "#f3f3f3",
            borderWidth: 1,
            tickColor: "#f3f3f3"
          },
          series: {
            shadowSize: 0, // Drawing is faster without shadows
            color: "#3c8dbc"
          },
          lines: {
            fill: true, //Converts the line chart to area chart
            color: "#3c8dbc"
          },
          yaxis: {
            min: 0,
            max: 20,
            show: true
          },
          xaxis: {
            min: 0,
            max: 90,
            show: false
          }
        });

         var updateInterval = 500; //Fetch data ever x milliseconds
        var realtime = "on"; //If == to on then fetch data every x seconds. else stop fetching
        function update() {

          interactive_plot.setData([getRandomData()]);
          interactive_plot2.setData([getRandomData2()]);

          // Since the axes don't change, we don't need to call plot.setupGrid()
          interactive_plot.draw();
          if (realtime === "on")
            setTimeout(update, updateInterval);

          interactive_plot2.draw();
          
        }

        //INITIALIZE REALTIME DATA FETCHING
        if (realtime === "on") {
          update();
        }
        //REALTIME TOGGLE
        $("#realtime .btn").click(function () {
          if ($(this).data("toggle") === "on") {
            realtime = "on";
          }
          else {
            realtime = "off";
          }
          update();
        });

         /*
         * END INTERACTIVE CHART
         */


    </script>
  </body>
</html>