<?php
namespace Application\Model;

 class ActionLog
 {
     public $id;
     public $timestamp;
     public $gitowner;
     public $ip;

     public function exchangeArray($data)
     {
         $this->id     = (!empty($data['id'])) ? $data['id'] : null;
         $this->timestamp = (!empty($data['timestamp'])) ? $data['timestamp'] : null;
         $this->gitowner  = (!empty($data['gitowner'])) ? $data['gitowner'] : null;
         $this->ip  = (!empty($data['ip'])) ? $data['ip'] : null;
     }
 }
