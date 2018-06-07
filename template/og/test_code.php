 <?php echo $MSG_Input?>:
         <textarea style="width:30%" cols=40 rows=5 id="input_text" name="input_text" ><?php echo $view_sample_input?></textarea>
         <?php echo $MSG_Output?>:
         <textarea style="width:30%" cols=40 rows=5 id="out" name="out" >SHOULD BE:


          <input id="TestRun" class="btn btn-info"  type=button value="<?php echo $MSG_TR?>" onclick=do_test_run();><span  class="btn"  id=result>Estado</span>
        <input type="reset"  class="btn btn-danger" value="Reset">
               <iframe name=testRun width=0 height=0 src="about:blank"></iframe>
<?php echo $view_sample_output?>

  function print_result(solution_id){
      sid=solution_id;
      $("#out").load("status-ajax.php?tr=1&solution_id="+solution_id);

    }