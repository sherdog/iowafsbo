
  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; IowaFSBOListings.com <?php echo date('Y'); ?></p>
    </div>
    <!-- /.container -->
  </footer>

   <!-- OUTPUT GOOGLE ANALYTICS CODE -->
    <?php
    if($settings['Google Analytics Code'] != ''){
      echo html_entity_decode(stripslashes($settings['Google Analytics Code']));
    }
    ?>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
