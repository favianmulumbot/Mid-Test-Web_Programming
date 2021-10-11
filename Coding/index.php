<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./style.css">

  <title>Bootstrap Mid Exam</title>

      <script>
    
        function tampilkan(){
          var nama_kota=document.getElementById("form1").faculty.value;
          if (nama_kota=="Komputer")
            {
                document.getElementById("prodi").innerHTML="<option value='SI (Sistem Infomasi)'>SI (Sistem Informasi)</option><option value='TI (Teknik Informatika)'>TI (Teknik Informatika)</option>";
            }
          else if (nama_kota=="Ekonomi")
            {
                document.getElementById("prodi").innerHTML="<option value='Akuntansi'>Akuntasi</option><option value='Manajement'>Manajement</option>";
            }
          else if (nama_kota=="Pendidikan")
            {
                document.getElementById("prodi").innerHTML="<option value='Pendidikan Agama'>Pendidikan Agama</option><option value='Pendidikan Bahasa Inggris'>Pendidikan Bahasa Inggris</option><option value='Pendidikan Ekonomi'>Pendidikan Ekonomi</option><option value='Luar Sekolah'>Luar Sekolah</option>";
            }
          else if (nama_kota=="Keperawatan")
            {
                document.getElementById("prodi").innerHTML="<option value='Profesi Ners'>Profesi Ners</option><option value='Keperawatan'>Keperawatan</option>";
            }
        }
      </script>
      
  <link href="<?php echo base_url(); ?>vendor/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>vendor/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>vendor/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-info">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= base_url('welcome') ?>">Student Management MiniApps</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?= base_url('welcome') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('welcome/about') ?>">About</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container mt-3">
    <h1>Student Management <Br> MiniApps</h1>
     <hr>
    <div class="row pt-4">
      <div class="col-6">
        <h4>Add Student</h4>

        <form action="<?php echo site_url('Welcome/input_data'); ?>" id="form1" name="form1"  method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="inputProdiName" class="form-label">Student ID NIM</label>
            <input type="text" name="NIM"  class="form-control" id="inputProdiName">
          </div>
          <div class="mb-3">
            <label for="inputProdiAbbreviation" class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" id="inputProdiAbbreviation">
          </div>
          <div class="form-group">
          <label for="inputProdiAbbreviation" class="form-label">Gender:</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" value="Laki-laki" checked>&nbsp; Laki-laki
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gender" value="Perempuan">&nbsp; Perempuan
            </div>
          </div>
          <div class="form-group">
            <label>Fakultas</label>
            <br>
            <select class="form-select" aria-label="Default select example" id="faculty" name="faculty" onchange="tampilkan()">
              <option selected value="Komputer">Komputer</option>
              <option value="Ekonomi">Ekonomi</option>
              <option value="Pendidikan">Pendidikan</option>
              <option value="Keperawatan">Keperawatan</option>
            </select>
          </div>
          <div class="form-group">
            <label>Program Studi</label>
            <br>
            <select class="form-select" aria-label="Default select example" id="prodi" name="prodi">
            </select>
          </div>
          <br>
          <button type="submit" class="btn btn-primary" id="form1" name="form1">Submit</button>

        </form>
      </div>
      <div class="col-6">
        <div class="mb-3">
          <h4>Filter Student By</h4>
          <div class="mb-3">
          <form action="<?php echo site_url('Welcome/filterdata_fak/'); ?>" method="post">
            <label for="inputProdiName" class="form-label">Faculty</label>
            <select class="form-select" aria-label="Default select example" id="faculty" name="faculty">
              <option selected value="Komputer">Komputer</option>
              <option value="Ekonomi">Ekonomi</option>
              <option value="Pendidikan">Pendidikan</option>
              <option value="Keperawatan">Keperawatan</option>
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Show Student</button>
          </form>
          </div>
          <div class="mb-3">
          <form  action="<?php echo site_url('Welcome/filterdata_prodi/'); ?>" method="post">
            <label for="inputProdiName" class="form-label">Program of study</label>
            <select class="form-select" name="prodi" aria-label="Default select example">
              <option selected value="SI (Sistem Infomasi)">SI Sistem Informasi</option>
              <option value="TI (Teknik Informatika)">TI (Teknik Informatika)</option>
              <option value="Akuntansi">Akuntansi</option>
              <option value="Manajement">Manajement</option>
              <option value="Pendidikan Agama">Pendidikan Agama</option>
              <option value="Pendidikan Bahasa Inggris">Pendidikan Bahasa Inggris</option>
              <option value="Luar Sekolah">Luar Sekolah</option>
              <option value="Profesi Ners">Profesi Ners</option>
              <option value="Keperawatan">Keperawatan</option>
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Show Student</button>
          </form>
          </div>
        </div>

      </div>

    <hr>
    
  </div>
        <div class="container-fluid">

        <h1 class="text-center text-uppercase">Our Student</h1>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>NIM</th>
                      <th>Name</th>
                      <th>Gender</th>
                      <th>Faculty</th>
                      <th>Abbreviation</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i=1; ?>
                      <?php foreach ($data as $d): ?>
                    <tr>
                      <td><?= $i; ?></td>
                      <td><?= $d['NIM'];?></td>
                      <td><?= $d['Full_Name'];?></td>
                      <td><?= $d['Gender'];?></td>
                      <td><?= $d['Fakultas'];?></td>
                      <td><?= $d['Program_study'];?></td>
                      <td><a href="<?php echo site_url('Welcome/delete_data/') . $d['Id']; ?>" class="link-danger" OnClick="return confirm('Are you sure you want to delete?')"><i class="bi bi-trash"></i></a></td>
                      <?php $i++; ?>
                      <?php endforeach ?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

  <footer class="py-4">
    <hr>
    <p class="text-center text-uppercase">&copy; 2021 by WebDevelopment Class</p>
  </footer>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>


  
  
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/js/sb-admin-2.min.js"></script>
  <!-- Page level plugins -->
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?php echo base_url(); ?>vendor/sbadmin2/js/demo/datatables-demo.js"></script>

</body>
</html>

