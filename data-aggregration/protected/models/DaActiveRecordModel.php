<?php
  class DaActiveRecordModel extends CActiveRecord {
    protected function afterValidate() {
      if($this->isNewRecord){
        $this->created_at = DaDbWrapper::now();
      }
      $this->modified_at = DaDbWrapper::now();
      return parent::afterValidate();
    }
  }
?>
