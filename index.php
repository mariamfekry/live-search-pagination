<html>
<head>
    <link rel="stylesheet" href="stylesheet.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/placeholder-loading/dist/css/placeholder-loading.min.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body class=bg-dark>
<br />
    <div class="container-fluid  p-3 my-3 bg-dark text-white">
      <h1 align="center">Courses</h1>
      <br />
      <div class="card border border-primary">
        <div class="card-body">
          <div class="form-group">
            <input type="text" name="search" id="search" class="form-control" placeholder="Search for course name, describtion , department name or professor name" />
          </div>
          <div class="table-responsive thead-dark" id="output">
            
          </div>
        </div>
      </div>
    </div>

</body>
</html>
<script type="text/javascript">
 $(document).ready(function(){

  load_data(1);

function load_data(page, query = '')
{
  $.ajax({
    url:"ajax.php",
    method:"POST",
    data:{page:page, query:query},
    success:function(data)
    {
      $('#output').html(data);
    }
  });
}

$(document).on('click', '.page-link', function(){
  var page = $(this).data('page_number');
  var query = $('#search').val();
  load_data(page, query);
});

$('#search').keyup(function(){
  var query = $('#search').val();
  load_data(1, query);
});

});
</script>
