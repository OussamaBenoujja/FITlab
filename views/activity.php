

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary pt-3">
    <div class="container-fluid">
        <h1><a class="navbar-brand">RESERVATIONS DE ACTIVITES DANS SALLE DE GYM</a></h1>
        <a class="btn btn-outline-success mx-2" href="home.php">HOME PAGE</a>
    </div>
</nav>



  <section class="flex justify-around mt-10 ">
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="relative">
        <img class="w-full h-100 object-cover" src="https://via.placeholder.com/600x360">
    </div>
    <div class="p-4">
        <div class="text-lg font-medium text-gray-800 mb-2">Title</div>
        <p class="text-gray-500 text-sm text-wrap">Lorem ipsum dolor sit amet</p>
        </div>

  </div>

<div>



<form method="POST" class="bg-white rounded-xl shadow-md overflow-hidden p-40">
    <div class="w-100">
        <label for="email">Email address</label>
        <input type="email" class="form-control" name="email" placeholder="Enter email" required>
    </div>
    <div>
    <select name="activity">
                <option value="" disabled selected>Select an Activity</option>
            

            </select>
    </div>
    <div class="">
        <label for="date">SELECT DATE</label>
        <input type="date" class="form-control" name="date" required>
    </div>
     

 
    <input type="submit" name="submit" class="btn btn-primary">
</form>

</div>


  </section>





  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>