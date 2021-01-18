<?php
include "config.php";
$record_per_page=5; //limit variable
$page=1;//page variable to store the current page number
//condition for updating page number and maintain the limit of 5
if($_POST['page']>1){
$start_from=( ($_POST['page']-1)*$record_per_page);
$page=$_POST['page'];

}
else{
$start_from=0;

}


$query="SELECT course.course_name, course.course_des, departments.dep_name,professor.prof_name
FROM ((course 
INNER JOIN departments ON course.dep_id = departments.dep_id)
INNER JOIN professor ON course.prof_id = professor.prof_id)";
//in case a search query is entered append where to sql statement
if($_POST['query']!=''){
  $query .= "WHERE REPLACE(course_name,' ','') LIKE REPLACE('"."%".$_POST['query']."%"."',' ','') 
  OR  REPLACE(course_des,' ','') LIKE REPLACE('"."%".$_POST['query']."%"."',' ','') 
  OR  REPLACE(dep_name,' ','') LIKE REPLACE('"."%".$_POST['query']."%"."',' ','') 
  OR  REPLACE(prof_name,' ','') LIKE REPLACE('"."%".$_POST['query']."%"."',' ','')";
}
$limitquery=$query.'LIMIT '.$start_from.','.$record_per_page.''; 
$statement=$conn->prepare($query);
$statement->execute();
$total_pages = $statement->rowCount();

$statement=$conn->prepare($limitquery);
$statement->execute();
$result=$statement->fetchAll();
$total_filter_data = $statement->rowCount();
//html to be returned starting with the table head
$output = '
<table class="table table-striped table-bordered  table-dark">
<thead  class="thead-dark">
  <tr>
    <th>Course name</th>
    <th>course describtion</th>
    <th>department name</th>
    <th>Professor name </th>
  </tr>
  </thead>
';
// if there's result display the limited query else display no data found
if($total_pages>0){
  foreach($result as $row)
  {
  
    $output .= '
    <tr>
      <td>'.$row['course_name'].'</td>
      <td>'.$row["course_des"].'</td>
      <td>'.$row["dep_name"].'</td>
      <td>'.$row["prof_name"].'</td>
    </tr>
    ';
  }

}
else{
  $output .= '
  <tr>
    <td colspan="4" align="center">No Data Found</td>
  </tr>
  ';

}
//closing the table
$output .= '
</table>
<br />
<div align="center" >
  <ul class="pagination">
';
//handling pagination and rounding the number to nearest higher int
$total = ceil($total_pages/$record_per_page);
$previous_link = '';
$next_link = '';
$page_link = '';
if($total_links>4){
  if($page<5){
    for($count=1;$count<=5;$count++)
    {
       $page_array[]=$count;
    }
    $page_array[]='...';
    $page_array[]=$total;
  }
  else{
    $end_limit=$total-5;
    if($page>$end_limit){
      $page_array[]=1;
      $page_array[]='...';
      for($count=$end_limit;$count<=$total;$count++)
      {
        $page_array[]=$count;
      }
    }
    else
    {
      $page_array[]=1;
      $page_array[]='...';
      for($count=$page-1;$count<=$page+1;$count++)
      {
        $page_array[]=$count;
      }
      $page_array[]='...';
      $page_array[]=$total;
    }

  }
}
else{
  for($count=1;$count<=$total;$count++){

    $page_array[]=$count;
  }
}
if(!$total_pages==0){
  
for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id > $total)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}
}
$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;



?>
