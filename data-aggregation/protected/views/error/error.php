<div class="form round" style="border: 1px solid #ccc;padding:10px;">
  <div class="title round action-title flash-error "> Error message :  <?php echo $error["message"]; ?> </div>
  
  <p style="background: #ddd;">
    <h1 class="action-title round" > Error detail </h1>
    <div class="row"> 
      <label> Code </label>
      <?php echo $error["code"] ?>
    </div>

    <div class="row"> 
      <label> Type </label>
      <?php echo $error["type"] ?>
    </div>
  </p>
  
  
  <a href="<?php echo $this->createUrl("/") ?>" class="btn-action round" > Go Back </a>
</div>  